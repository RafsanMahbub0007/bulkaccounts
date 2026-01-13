<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
            $tabs->push('sold'); // update local list
        }

        // Step 2: Prepare sold emails list
        $soldEmails = $accounts->pluck('email')
            ->filter()
            ->map(fn($e) => strtolower(trim($e)))
            ->toArray();

        // Step 3: Remove sold emails from all unsold tabs
        foreach ($tabs as $tab) {
            if (strtolower($tab) === 'sold') continue;

            $range = "{$tab}!A1:Z";
            $response = $service->spreadsheets_values->get($sheetId, $range);
            $rows = $response->getValues() ?? [];
            if (count($rows) < 2) continue; // skip if only headers

            $headers = array_map('strtolower', $rows[0]);
            $emailIndex = array_search('email', $headers);
            if ($emailIndex === false) continue;

            // Filter rows without reading entire sheet
            $filtered = [$rows[0]];
            foreach (array_slice($rows, 1) as $row) {
                $row = array_pad($row, count($headers), '');
                if (!in_array(strtolower($row[$emailIndex] ?? ''), $soldEmails)) {
                    $filtered[] = $row;
                }
            }

            // Clear old data below header
            $service->spreadsheets_values->clear(
                $sheetId,
                "{$tab}!A2:Z",
                new \Google\Service\Sheets\ClearValuesRequest()
            );

            // Update only filtered rows
            $service->spreadsheets_values->update(
                $sheetId,
                "{$tab}!A1",
                new \Google\Service\Sheets\ValueRange(['values' => $filtered]),
                ['valueInputOption' => 'RAW']
            );
        }

        // Step 4: Append sold accounts to 'sold' tab
        $response = $service->spreadsheets_values->get($sheetId, "sold!A1:Z");
        $sheetRows = $response->getValues() ?? [];

        $sheetHeaders = [];
        if (empty($sheetRows)) {
            // If sold tab is empty, create headers
            $sheetHeaders = array_merge(['email'], $accounts->first()->meta_headers ?? []);
            $service->spreadsheets_values->update(
                $sheetId,
                "sold!A1",
                new \Google\Service\Sheets\ValueRange(['values' => [$sheetHeaders]]),
                ['valueInputOption' => 'RAW']
            );
        } else {
            $sheetHeaders = $sheetRows[0];
        }

        // Prepare rows to append
        $rowsToAppend = $accounts->map(function ($account) use ($sheetHeaders) {
            $meta = is_array($account->meta) ? $account->meta : [];
            return array_map(
                fn($header) =>
                strtolower(trim($header)) === 'email' ? $account->email ?? '' : $meta[strtolower(trim($header))] ?? '',
                $sheetHeaders
            );
        })->toArray();

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
