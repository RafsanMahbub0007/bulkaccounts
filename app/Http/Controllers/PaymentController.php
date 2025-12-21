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
                // Update order payment status and other fields
                $order->update([
                    'payment_status' => 'paid',
                    'order_status'   => 'completed',
                    'completed_at'   => now(),
                    'transaction_reference' => $data['payment_id'] ?? null,
                ]);

                // Process items and stock adjustments
                foreach ($order->orderItems() as $item) {
                    $accounts = ProductAccount::where('product_id', $item->product_id)
                        ->where('is_sold', 0)
                        ->limit($item->quantity)
                        ->lockForUpdate()
                        ->get();

                    if ($accounts->count() < $item->quantity) {
                        throw new \Exception('Insufficient stock during fulfillment');
                    }

                    foreach ($accounts as $acc) {
                        $acc->update(['is_sold' => 1]);
                    }

                    $item->product->decrement('stock', $item->quantity);

                    Storage::makeDirectory('orders');
                    $filePath = "orders/order_{$order->id}_item_{$item->id}.xlsx";
                    Excel::store(new AccountsExport($accounts), $filePath);

                    $order->update([
                        'download_file' => $filePath,
                    ]);
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
            Log::error('Error in completing order: ' . $e->getMessage());
        }
    }
}
