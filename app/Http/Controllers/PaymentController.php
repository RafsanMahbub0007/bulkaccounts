<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
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

        $order = Order::where('order_number', $data['order_id'])->first();
        if (!$order) return response()->json(['status' => 'order_not_found']);

        if ($order->payment_status === 'paid') {
            return response()->json(['status' => 'already_processed']);
        }

        if ($data['payment_status'] === 'finished') {
            try {
                app(OrderFulfillmentService::class)->fulfillOrder($order, $data);
            } catch (\Exception $e) {
                Log::error("Fulfillment failed for order {$order->id}: " . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
