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

class PaymentController extends Controller
{
    /**
     * NOWPayments IPN Webhook
     */
    public function handle(Request $request)
    {
        Log::info('NowPayments IPN RAW BODY', [
        'body' => $request->getContent(),
        'headers' => $request->headers->all(),
    ]);

    Log::info('NowPayments IPN PARSED', $request->all());

    return response()->json(['status' => 'OK']);
        // 1️⃣ Verify signature
        $signature = $request->header('x-nowpayments-sig');
        $payload   = $request->getContent();

        $expected = hash_hmac(
            'sha512',
            $payload,
            config('services.payment.secret')
        );

        if (!hash_equals($expected, $signature)) {
            Log::warning('Invalid NOWPayments signature');
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $data = $request->all();
        if (!isset($data['order_id'], $data['payment_status'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // 2️⃣ Find order by order_number
        $order = Order::where('order_number', $data['order_id'])->first();

        if (!$order) {
            return response()->json(['status' => 'order_not_found']);
        }

        // Prevent duplicate fulfillment
        if ($order->payment_status === 'paid') {
            return response()->json(['status' => 'already_processed']);
        }

        // 3️⃣ Handle payment status
        match ($data['payment_status']) {

            'finished', 'confirmed' => $this->completeOrder($order, $data),

            'partially_paid' => $order->update([
                'payment_status' => 'partially_paid',
                'order_status'   => 'processing',
            ]),

            'failed', 'expired' => $order->update([
                'payment_status' => 'failed',
                'order_status'   => 'cancelled',
            ]),

            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    /**
     * Fulfill paid order (ONLY place where stock/files are handled)
     */
    private function completeOrder(Order $order, array $data)
    {
        DB::transaction(function () use ($order, $data) {

            $order->update([
                'payment_status' => 'paid',
                'order_status'   => 'completed',
                'paid_at'        => now(),
                'transaction_reference' => $data['payment_id'] ?? null,
            ]);

            foreach ($order->items as $item) {

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
                    'is_delivered'  => true,
                ]);
            }
        });
    }
    public function success(Order $order)
    {
        // ✅ Now $order is a model
        $order->payment_status;
    }
}
