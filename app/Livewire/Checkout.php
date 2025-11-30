<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Session;

class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $name;
    public $email;
    public $number;

    public function mount()
    {
        // Prefill user data
        if (auth()->check()) {
            $this->name   = auth()->user()->name;
            $this->email  = auth()->user()->email;
            $this->number = auth()->user()->phone;
        }

        // Load Cart
        $this->cartItems = Session::get('cart', []);
        $this->total = collect($this->cartItems)
            ->sum(fn($i) => $i['price'] * $i['quantity']);
    }

    public function proceedToPayment()
    {

        session()->put('checkout_data', [
            'name'   => $this->name,
            'email'  => $this->email,
            'number' => $this->number,
        ]);

        session()->save();
// dd(session('checkout_data'));
        return $this->redirectRoute('checkout.process', navigate: true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.checkout');
    }
}
