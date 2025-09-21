<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartSidebar extends Component
{
    public $cartItems = [];
    public $totalCarts = 0;
    public $totalItems = 0;
    public $totalPrice = 0;

    public function mount()
    {
        $this->loadCart();
    }

    #[On('cartUpdated')]
    public function loadCart()
    {
        $cart = session('cart', []);
        $this->cartItems = $cart;
        $this->totalCarts = count($cart);
        $this->totalItems = array_sum(array_column($cart, 'quantity'));
        $this->totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
    }

    public function render()
    {
        return view('livewire.cart-sidebar');
    }
}
