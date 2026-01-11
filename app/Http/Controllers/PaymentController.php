<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\ProductAccount;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccountsExport;
use App\Models\Payment;
use Google\Client;
use Google\Service\Sheets;

use function Symfony\Component\Clock\now;

class PaymentController extends Controller
{

    public function handle(Request $request)
    {
        // Log::info('NowPayments IPN RAW BODY', [
        //     'body' => $request->getContent(),
        //     'headers' => $request->headers->all(),
        // ]);

        // Log::info('NowPayments IPN PARSED', $request->all());

        // Verify signature
        // $signature = $request->header('x-nowpayments-sig');  // Corrected method to fetch header
        // $payload   = $request->getContent();

        // Log::info('Signature:', ['signature' => $signature]);
        // Log::info('Payload:', ['payload' => $payload]);

        // $expected = hash_hmac('sha512',$payload,config('services.payment.secret'));

        // Log::info('Expected Signature:', ['expected' => $expected]);

        // if (!hash_equals($expected, $signature)) {
        //     Log::warning('Invalid NOWPayments signature');
        //     return response()->json(['error' => 'Invalid signature'], 403);
        // }

        // Log::info('Signature Verified: ', ['status' => 'valid']);

        $data = $request->all();

        if (!isset($data['order_id'], $data['payment_status'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // Find the order by order_number
        $order = Order::where('order_number', $data['order_id'])->first();

        if (!$order) {
            return response()->json(['status' => 'order_not_found']);
        }

        // Prevent duplicate fulfillment
        if ($order->payment_status === 'paid') {
            return response()->json(['status' => 'already_processed']);
        }

        // Handle payment status
        switch ($data['payment_status']) {
            case 'finished':
                $this->completeOrder($order, $data);
                break;
            case 'partially_paid':
                $order->update([
                    'payment_status' => 'partially_paid',
                    'order_status'   => 'processing',
                ]);
                break;
            case 'failed':
            case 'expired':
                $order->update([
                    'payment_status' => 'failed',
                    'order_status'   => 'cancelled',
                ]);
                break;
            default:
                break;
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Fulfill paid order (ONLY place where stock/files are handled)
     */


private function completeOrder(Order $order, array $data)
{
    try {
        DB::transaction(function () use ($order, $data) {

            // Update order status
            $order->update([
                'payment_status' => 'paid',
                'order_status'   => 'completed',
                'completed_at'   => now(),
                'transaction_reference' => $data['payment_id'] ?? null,
            ]);

            foreach ($order->orderItems() as $item) {

                $accounts = ProductAccount::where('product_id', $item->product_id)
                    ->where('status', 'unsold')
                    ->limit($item->quantity)
                    ->lockForUpdate()
                    ->get();

                if ($accounts->count() < $item->quantity) {
                    throw new \Exception('Insufficient stock during fulfillment');
                }

                $soldRows = [];
                $googleSheetId = $item->product->google_sheet_id;

                // Prepare Google Sheets client if sheet exists
                $service = null;
                $tabs = [];
                if ($googleSheetId) {
                    $client = new Client();
                    $client->setApplicationName('Order Completion Dynamic Sync');
                    $client->setScopes([Sheets::SPREADSHEETS]);
                    $client->setAuthConfig(config('services.google.credentials'));
                    $service = new Sheets($client);

                    $spreadsheet = $service->spreadsheets->get($googleSheetId);
                    $tabs = collect($spreadsheet->getSheets())
                        ->map(fn($s) => $s->getProperties()->getTitle())
                        ->toArray();
                }

                foreach ($accounts as $acc) {
                    // Update DB status
                    $acc->update(['status' => 'sold']);
                    $soldRows[] = array_values($acc->meta); // Collect row for Google Sheet
                }

                $item->product->decrement('stock', $item->quantity);

                // Export Excel for order download
                Storage::makeDirectory('orders');
                $filePath = "orders/order_{$order->id}_item_{$item->id}.xlsx";
                Excel::store(new AccountsExport($accounts), $filePath);
                $order->update(['download_file' => $filePath]);

                // 🔹 Google Sheets update
                if ($service && !empty($soldRows)) {
                    try {
                        // 1️⃣ Remove sold accounts from any tab
                        foreach ($tabs as $tabName) {
                            $range = $tabName; // entire tab
                            $response = $service->spreadsheets_values->get($googleSheetId, $range);
                            $rows = $response->getValues();

                            if (!$rows || count($rows) < 1) continue;

                            $headers = $rows[0];
                            $dataRows = array_slice($rows, 1);

                            // Filter out sold accounts
                            $filteredRows = array_filter($dataRows, function ($row) use ($soldRows) {
                                $email = strtolower($row[0] ?? '');
                                foreach ($soldRows as $sold) {
                                    if ($email === strtolower($sold[0] ?? '')) return false;
                                }
                                return true;
                            });

                            // Add headers back
                            array_unshift($filteredRows, $headers);

                            $body = new \Google\Service\Sheets\ValueRange(['values' => $filteredRows]);
                            $service->spreadsheets_values->update($googleSheetId, $range, $body, ['valueInputOption' => 'RAW']);
                        }

                        // 2️⃣ Append sold accounts to sold tab (create if missing)
                        $soldTab = 'sold';
                        if (!in_array($soldTab, $tabs)) {
                            $service->spreadsheets->batchUpdate($googleSheetId, new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest([
                                'requests' => [
                                    ['addSheet' => ['properties' => ['title' => $soldTab]]]
                                ]
                            ]));
                        }

                        $soldRange = $soldTab;
                        $body = new \Google\Service\Sheets\ValueRange(['values' => $soldRows]);
                        $service->spreadsheets_values->append($googleSheetId, $soldRange, $body, ['valueInputOption' => 'RAW']);

                    } catch (\Throwable $e) {
                        Log::error('Google Sheets update failed: ' . $e->getMessage());
                    }
                }
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'amount' => $data['price_amount'],
                'currency' => $data['price_currency'],
                'status' => 'completed',
                'transaction_id' => $data['payment_id'] ?? null,
                'paid_at' => now(),
            ]);
        });
    } catch (\Exception $e) {
        Log::error('Error completing order: ' . $e->getMessage());
    }
}

}
