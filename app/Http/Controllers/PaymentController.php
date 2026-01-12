<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\ProductAccount;
use App\Models\Payment;
use Google\Client;
use Google\Service\Sheets;

class PaymentController extends Controller
{
    private function sheets(): Sheets
    {
        $client = new Client();
        $client->setApplicationName('Order Fulfillment');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(config('services.google.credentials'));

        return new Sheets($client);
    }

    public function handle(Request $request)
    {
        $data = $request->all();

        if (!isset($data['order_id'], $data['payment_status'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $order = Order::where('order_number', $data['order_id'])->first();
        if (!$order) return response()->json(['status' => 'order_not_found']);

        if ($order->payment_status === 'paid') {
            return response()->json(['status' => 'already_processed']);
        }

        if ($data['payment_status'] === 'finished') {
            $this->completeOrder($order, $data);
        }

        return response()->json(['status' => 'ok']);
    }

    /* ================= FULFILLMENT ================= */
    private function completeOrder(Order $order, array $payload): void
    {
        DB::transaction(function () use ($order, $payload) {

            $service = $this->sheets();

            $order->update([
                'payment_status' => 'paid',
                'order_status'   => 'completed',
                'completed_at'   => now(),
                'transaction_reference' => $payload['payment_id'] ?? null,
            ]);

            foreach ($order->items as $item) {

                $accounts = ProductAccount::where('product_id', $item->product_id)
                    ->where('status', 'unsold')
                    ->lockForUpdate()
                    ->limit($item->quantity)
                    ->get();

                if ($accounts->count() < $item->quantity) {
                    throw new \Exception('Insufficient stock');
                }

                foreach ($accounts as $acc) {
                    $acc->update(['status' => 'sold']);
                }

                $item->product->decrement('stock', $item->quantity);

                if ($item->product->google_sheet_id) {
                    $this->updateGoogleSheet(
                        $service,
                        $item->product->google_sheet_id,
                        $accounts
                    );
                }
            }

            Payment::create([
                'order_id' => $order->id,
                'amount' => $payload['price_amount'] ?? 0,
                'currency' => $payload['price_currency'] ?? 'USD',
                'status' => 'completed',
                'transaction_id' => $payload['payment_id'] ?? null,
                'paid_at' => now(),
            ]);
        });
    }

    /* ================= GOOGLE SHEET UPDATE ================= */
    private function updateGoogleSheet(Sheets $service, string $sheetId, $accounts): void
    {
        $spreadsheet = $service->spreadsheets->get($sheetId);
        $tabs = collect($spreadsheet->getSheets())
            ->map(fn ($s) => $s->getProperties()->getTitle());

        $soldEmails = $accounts->pluck('email')->map(fn ($e) => strtolower($e))->toArray();

        foreach ($tabs as $tab) {
            $response = $service->spreadsheets_values->get($sheetId, "{$tab}!A1:Z");
            $rows = $response->getValues();
            if (!$rows || count($rows) < 2) continue;

            $headers = $rows[0];
            $emailIndex = array_search('email', array_map('strtolower', $headers));
            if ($emailIndex === false) continue;

            $filtered = [$headers];

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

        // Append to SOLD tab
        if (!$tabs->contains('sold')) {
            $service->spreadsheets->batchUpdate($sheetId,
                new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest([
                    'requests' => [['addSheet' => ['properties' => ['title' => 'sold']]]]
                ])
            );
        }

        $headers = $accounts->first()->meta_headers;

        $rows = $accounts->map(fn ($a) =>
            array_map(fn ($h) => $a->meta[$h] ?? '', $headers)
        )->toArray();

        $service->spreadsheets_values->append(
            $sheetId,
            "sold!A1",
            new \Google\Service\Sheets\ValueRange(['values' => $rows]),
            ['valueInputOption' => 'RAW']
        );
    }
}
