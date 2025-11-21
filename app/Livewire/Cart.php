<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $totalPrice = 0;

    protected $listeners = ['cartUpdated' => 'updateCart'];

    public function mount()
    {
        $this->updateCart();
    }

    public function updateCart()
    {
        // Fetch fresh data from session
        $cart = session()->get('cart', []);

        // Update cart items and total price
        $this->cartItems = $cart;
        $this->totalPrice = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            $this->dispatch('cartUpdated');
            $this->updateCart();
        }
    }

    public function updateQuantity($productId, $quantity)
    {
        // Fetch the cart from session
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $product = $cart[$productId];
            // Check if the requested quantity is within the available stock stored in the cart
            if ($quantity > $product['stock']) {
                session()->flash('error', 'Not enough stock available.');
                $this->dispatch('cartUpdateFailed');
                return;
            }

            // If quantity is 0 or less, remove the item
            if ($quantity <= 0) {
                return;
            } else {
                // Update the quantity
                $cart[$productId]['quantity'] = $quantity;
                session()->put('cart', $cart);

                // Dispatch event after updating quantity
                $this->dispatch('cartUpdated');
                $this->updateCart();
            }
        }
    }


    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.cart', [
            'cartItems' => $this->cartItems,
            'totalPrice' => $this->totalPrice,
        ]);
    }
}
