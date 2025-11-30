<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccountsExport;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $data = session('checkout_data');
        $cart = Session::get('cart', []);

        if (!$data || empty($cart)) {
            return redirect()->route('checkout')->with('error', 'Checkout data missing or cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id'        => auth()->id(),
                'order_number'   => strtoupper(uniqid('ORD-')),
                'total_price'    => collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']),
                'payment_status' => 'unpaid',
                'order_status'   => 'pending',
                'ordered_at'     => now(),
                'name'           => $data['name'],
                'email'          => $data['email'],
                'phone'          => $data['number'],
            ]);

            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);

                $accounts = ProductAccount::where('product_id', $product->id)
                    ->where('is_sold', false)
                    ->limit($item['quantity'])
                    ->get();

                if ($accounts->count() < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart')->with('error', "Not enough stock for {$product->name}");
                }

                // Create order item
                $orderItem = OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $product->id,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                // Mark accounts sold
                foreach ($accounts as $acc) {
                    $acc->update([
                        'is_sold'       => true,
                        'order_item_id' => $orderItem->id,
                    ]);
                }

                // Ensure downloads folder exists
                Storage::disk('public')->makeDirectory('downloads');

                // Export accounts
                $file = "downloads/order_item_{$orderItem->id}.xlsx";
                Excel::store(new AccountsExport($accounts), $file, 'public');
                $orderItem->update(['download_file' => $file]);

                // Reduce stock
                $product->decrement('stock', $item['quantity']);
            }

            // TEST MODE
            if (config('services.payment.test_mode')) {
                DB::commit();
                Session::forget('cart');
                return redirect()->route('user.orders')->with('success', 'Order placed in test mode.');
            }

            // LIVE PAYMENT — NowPayments
            $response = Http::withHeaders([
                'x-api-key' => config('services.payment.api_key')
            ])->post('https://api.nowpayments.io/v1/payment', [
                'price_amount'      => (float) $order->total_price,
                'price_currency'    => 'usd',      // fiat price
                'pay_currency'      => 'usdt',     // crypto payment
                'order_id'          => $order->order_number,
                'order_description' => "Order {$order->order_number}",
                'ipn_callback_url'  => url('/api/payment/callback'),
                'success_url'       => route('payment.success', $order->id),
                'cancel_url'        => route('payment.cancel', $order->id),
            ]);

            $data = $response->json();

            if (!$data || empty($data['invoice_url'])) {
                DB::rollBack();
                return redirect()->route('checkout')->with('error', 'Payment initialization failed.');
            }

            DB::commit();
            Session::forget('cart');

            return redirect()->away($data['invoice_url']);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('checkout')->with('error', "Error: {$e->getMessage()}");
        }
    }
}
