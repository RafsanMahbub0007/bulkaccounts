<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Validate;

class UserOrderDetails extends Component
{
    public $order;
    public function mount(Order $order)
    {
        $this->order = $order->load([
            'orderItems.deliveries',
            'payments', // Load the payments relationship
        ]);

        if (!$this->order) {
            session()->flash('error', 'Order not found.');
            return redirect()->route('user.orders');
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.user-order-details', [
            'payments' => $this->order->payments, // Pass payments data to the view
        ]);
    }
}
