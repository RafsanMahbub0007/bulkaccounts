<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\PreOrder;
use App\Models\ProductAccount;
use App\Models\Payment;
use App\Services\OrderFulfillmentService;

class PaymentController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        if (!isset($data['order_id'], $data['payment_status'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $orderId = $data['order_id'];
        $order = Order::where('order_number', $orderId)->first();
        $preOrder = PreOrder::where('order_number', $orderId)->first();

        if (!$order && !$preOrder) {
            return response()->json(['status' => 'order_not_found']);
        }

        // Check if already processed
        if (($order && $order->payment_status === 'paid') || ($preOrder && $preOrder->payment_status === 'paid')) {
            return response()->json(['status' => 'already_processed']);
        }

        if ($data['payment_status'] === 'finished') {
            try {
                // Fulfill Regular Order
                if ($order) {
                    app(OrderFulfillmentService::class)->fulfillOrder($order, $data);
                }

                // Fulfill Pre-Order (Mark as paid, wait for admin)
                if ($preOrder) {
                    $preOrder->update([
                        'payment_status' => 'paid',
                        'status' => 'processing', // Waiting for admin to fulfill
                    ]);
                    // Optionally send confirmation email specifically for pre-order?
                    // Or rely on generic "Order Received" email if implemented.
                }

            } catch (\Exception $e) {
                Log::error("Fulfillment failed for order {$orderId}: " . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
