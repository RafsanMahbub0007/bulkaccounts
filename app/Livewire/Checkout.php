<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;

class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;

    public function mount()
    {
        $this->cartItems = Session::get('cart', []);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = array_reduce($this->cartItems, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function placeOrder()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            session()->flash('error', 'Your cart is empty.');
            return redirect()->route('checkout');
        }


        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => strtoupper(uniqid('ORD-')),
                'total_price' => array_reduce($cart, function ($carry, $item) {
                    return $carry + ($item['price'] * $item['quantity']);
                }, 0),
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'ordered_at' => now(),
            ]);

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

            DB::commit();

            session()->forget('cart');

            session()->flash('success', 'Order placed successfully');

            return redirect()->route('user.orders');
        } catch (\Exception $e) {

            DB::rollBack();

            session()->flash('error', 'Something went wrong. Please try again.');

            return redirect()->route('checkout');
        }
    }


    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.checkout');
    }
}
