<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Str;

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

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
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

    /* ================= GOOGLE SHEET CLIENT ================= */
    public function sheetsClient(): Sheets
    {
        $client = new Client();
        $client->setApplicationName('Order Fulfillment');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(config('services.google.credentials'));

        return new Sheets($client);
    }

    /**
     * Updates Google Sheet for a product and sold accounts
     */
    public function updateGoogleSheet(string $sheetId, $accounts): void
    {
        if ($accounts->isEmpty()) return;

        $service = $this->sheetsClient();

        // Step 1: Ensure 'sold' tab exists
        $spreadsheet = $service->spreadsheets->get($sheetId);
        $tabs = collect($spreadsheet->getSheets())
            ->map(fn($s) => $s->getProperties()->getTitle());

        if (!$tabs->contains('sold')) {
            $service->spreadsheets->batchUpdate(
                $sheetId,
                new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest([
                    'requests' => [['addSheet' => ['properties' => ['title' => 'sold']]]]
                ])
            );
            $tabs->push('sold');
        }

        // Step 2: Prepare sold emails list
            $soldEmails = $accounts->pluck('email')
                ->filter()
                ->map(fn($e) => strtolower(trim($e)))
                ->toArray();

            // Step 3: Remove sold emails from other tabs
            foreach ($tabs as $tab) {
                if (strtolower($tab) === 'sold') continue;

                $range = "{$tab}!A1:Z";
                $response = $service->spreadsheets_values->get($sheetId, $range);
                $rows = $response->getValues() ?? [];
                if (count($rows) < 2) continue;

                $headers = array_map('strtolower', $rows[0]);
                $emailIndex = array_search('email', $headers);
                if ($emailIndex === false) continue;

                $filtered = [$rows[0]];
                foreach (array_slice($rows, 1) as $row) {
                    $row = array_pad($row, count($headers), '');
                    if (!in_array(strtolower($row[$emailIndex] ?? ''), $soldEmails)) {
                        $filtered[] = $row;
                    }
                }

                $service->spreadsheets_values->clear(
                    $sheetId,
                    "{$tab}!A2:Z",
                    new \Google\Service\Sheets\ClearValuesRequest()
                );

                $service->spreadsheets_values->update(
                    $sheetId,
                    "{$tab}!A1",
                    new \Google\Service\Sheets\ValueRange(['values' => $filtered]),
                    ['valueInputOption' => 'RAW']
                );
            }

        // Step 4: Prepare sold tab headers
        $response = $service->spreadsheets_values->get($sheetId, "sold!A1:Z");
        $sheetRows = $response->getValues() ?? [];

        if (empty($sheetRows)) {
            // If sold tab is empty, create headers from first account
            $firstAccount = $accounts->first();
            $metaHeaders = $firstAccount->meta_headers ?? [];
            $sheetHeaders = array_merge(['Email'], $metaHeaders);

            $service->spreadsheets_values->update(
                $sheetId,
                "sold!A1",
                new \Google\Service\Sheets\ValueRange(['values' => [$sheetHeaders]]),
                ['valueInputOption' => 'RAW']
            );
        } else {
            $sheetHeaders = $sheetRows[0];
        }

            $rowsToAppend = $accounts->map(function ($account) use ($sheetHeaders) {
                $meta = $account->meta ?? [];
                $metaHeaders = $account->meta_headers ?? [];
                
                // Map meta headers to meta values
                $metaMap = [];
                foreach ($metaHeaders as $i => $header) {
                    if (isset($meta[$i])) {
                        $metaMap[strtolower($header)] = $meta[$i];
                    }
                }

                $row = [];

                foreach ($sheetHeaders as $index => $header) {
                    if ($index === 0 || strtolower($header) === 'email') {
                        $row[] = $account->email ?? '';
                    } else {
                        // Normalize header to match meta keys
                        $key = strtolower($header); 
                        $row[] = $metaMap[$key] ?? '';
                    }
                }

                return $row;
            })->toArray();


        // Step 6: Append to sold tab
        if (!empty($rowsToAppend)) {
            $service->spreadsheets_values->append(
                $sheetId,
                "sold!A1",
                new \Google\Service\Sheets\ValueRange(['values' => $rowsToAppend]),
                ['valueInputOption' => 'RAW']
            );
        }
    }
}
