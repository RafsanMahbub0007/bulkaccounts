<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;

class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $name, $email, $number;

    public function mount()
    {
        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->email = auth()->user()->email;
            $this->number = auth()->user()->phone ?? '';
        }
        $this->cartItems = Session::get('cart', []);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = array_reduce($this->cartItems, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function proceedToPayment()
    {
        if (!$this->name || !$this->email || !$this->number) {
            session()->flash('error', 'Please fill in all fields.');
            return;
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            session()->flash('error', 'Your cart is empty.');
            return redirect()->route('checkout');
        }

        try {
            DB::beginTransaction();

            // ✅ 1️⃣ Create the order record
            $order = Order::create([
                'user_id' => Auth::id() ?? null,
                'order_number' => strtoupper(uniqid('ORD-')),
                'total_price' => $this->total,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'ordered_at' => now(),
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->number,
            ]);

            // ✅ 2️⃣ Store each item
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                Product::find($item['id'])->decrement('stock', $item['quantity']);
            }

            // ✅ 3️⃣ Check if test mode is active
            if (env('PAYMENT_TEST_MODE', true)) {
                DB::commit();
                session()->forget('cart');
                session()->flash('success', '✅ Order placed successfully (test mode - payment skipped)');
                return redirect()->route('user.orders');
            }

            // ✅ 4️⃣ Proceed with NOWPayments (only in live mode)
            $response = Http::withHeaders([
                'x-api-key' => env('NOWPAYMENTS_API_KEY'),
            ])->post('https://api.nowpayments.io/v1/payment', [
                'price_amount' => $this->total,
                'price_currency' => 'usd',
                'pay_currency' => 'usdt',
                'order_id' => $order->order_number,
                'order_description' => 'Order #' . $order->order_number,
                'ipn_callback_url' => url('/api/payment/callback'), // for automatic payment verification
                'success_url' => route('payment.success', ['order' => $order->id]),
                'cancel_url' => route('payment.cancel', ['order' => $order->id]),
            ]);

            $data = $response->json();

            if (isset($data['invoice_url'])) {
                DB::commit();
                session()->forget('cart');
                return redirect()->away($data['invoice_url']);
            }

            DB::rollBack();
            session()->flash('error', 'Unable to process payment. Please try again.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.checkout');
    }
}
