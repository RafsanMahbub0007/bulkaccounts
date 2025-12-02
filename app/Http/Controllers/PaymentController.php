<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        $data = $request->all();

        if (!isset($data['order_id']) || !isset($data['payment_status'])) {
            return response()->json(['error' => 'Invalid callback'], 400);
        }

        if (in_array($data['payment_status'], ['finished', 'confirmed'])) {

            $order = Order::where('order_number', $data['order_id'])->first();

            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'order_status'   => 'completed',
                    'paid_at'        => now(),
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
