<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Google\Client;
use Google\Service\Sheets;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'payment_status',
        'order_status',
        'ordered_at',
        'completed_at',
        'download_file',
    ];

    protected $casts = [
        'ordered_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate order number
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });

        // Trigger when order is updated
        static::updated(function ($order) {
            if (
                $order->order_status === 'completed' &&
                $order->payment_status === 'paid' &&
                $order->completed_at
            ) {
                $order->handleAccountsSold();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function deliveries()
    {
        return $this->hasManyThrough(Delivery::class, OrderItem::class);
    }

    /* ================= MARK ACCOUNTS AS SOLD ================= */
    public function handleAccountsSold(): void
    {
        DB::transaction(function () {
            foreach ($this->orderItems as $item) {
                $product = $item->product;
                if (!$product) continue;

                // Lock and fetch only required unsold accounts
                $accounts = $product->accounts()
                    ->where('status', 'unsold')
                    ->lockForUpdate()
                    ->limit($item->quantity)
                    ->get();

                if ($accounts->count() < $item->quantity) {
                    throw new \Exception("Insufficient stock for product {$product->name}");
                }

                // Mark as sold
                foreach ($accounts as $acc) {
                    $acc->update(['status' => 'sold']);
                }

                // Decrement stock
                $product->decrement('stock', $item->quantity);

                // Update Google Sheet
                if ($product->google_sheet_id) {
                    $this->updateGoogleSheet($product, $accounts);
                }
            }
        });
    }

    /* ================= GOOGLE SHEET CLIENT ================= */
    private function sheetsClient(): Sheets
    {
        $client = new Client();
        $client->setApplicationName('Order Fulfillment');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(config('services.google.credentials'));

        return new Sheets($client);
    }

    private function updateGoogleSheet($product, $accounts): void
    {
        if ($accounts->isEmpty()) return;

        $sheetId = $product->google_sheet_id;
        $service = $this->sheetsClient();

        // Fetch spreadsheet and tabs
        $spreadsheet = $service->spreadsheets->get($sheetId);
        $tabs = collect($spreadsheet->getSheets())
            ->map(fn($s) => $s->getProperties()->getTitle());

        $soldEmails = $accounts->pluck('email')->map(fn($e) => strtolower($e))->toArray();

        // Remove sold emails from all unsold tabs (skip "sold")
        foreach ($tabs as $tab) {
            if (strtolower($tab) === 'sold') continue; // skip sold tab

            $response = $service->spreadsheets_values->get($sheetId, "{$tab}!A1:Z");
            $rows = $response->getValues();
            if (!$rows || count($rows) < 2) continue;

            $headers = $rows[0];
            $emailIndex = array_search('email', array_map('strtolower', $headers));
            if ($emailIndex === false) continue;

            $filtered = [$headers]; // always include headers
            foreach (array_slice($rows, 1) as $row) {
                $email = strtolower($row[$emailIndex] ?? '');
                if (!in_array($email, $soldEmails)) {
                    $filtered[] = $row;
                }
            }

            $service->spreadsheets_values->update(
                $sheetId,
                "{$tab}!A1",
                new \Google\Service\Sheets\ValueRange(['values' => $filtered]),
                ['valueInputOption' => 'RAW']
            );
        }

        // Create "sold" tab if missing
        if (!$tabs->contains('sold')) {
            $service->spreadsheets->batchUpdate($sheetId, new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest([
                'requests' => [['addSheet' => ['properties' => ['title' => 'sold']]]]
            ]));

            // refresh tabs after creating sold tab
            $spreadsheet = $service->spreadsheets->get($sheetId);
            $tabs = collect($spreadsheet->getSheets())
                ->map(fn($s) => $s->getProperties()->getTitle());
        }

        // Prepare rows to append to sold tab
        $headers = $accounts->first()->meta_headers ?? [];
        if (!empty($headers)) {
            $rows = $accounts->map(
                fn($a) =>
                array_map(fn($h) => $a->meta[$h] ?? '', (array)$a->meta)
            )->toArray();

            // Prepend headers if sold tab is empty
            $response = $service->spreadsheets_values->get($sheetId, "sold!A1:Z");
            $existingRows = $response->getValues() ?? [];
            if (empty($existingRows)) {
                array_unshift($rows, $headers);
            }

            $service->spreadsheets_values->append(
                $sheetId,
                "sold!A1",
                new \Google\Service\Sheets\ValueRange(['values' => $rows]),
                ['valueInputOption' => 'RAW']
            );
        }
    }
}
