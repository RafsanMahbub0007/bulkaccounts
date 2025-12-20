<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;

class PaymentStatus extends Component
{
        #[Layout('layouts.app')]
    public $orderId;

    public function mount($orderId)
    {
        $this->orderId = $orderId;
    }

    public function render()
    {

        return view('livewire.payment-status', [
            'order' => Order::find($this->orderId),
        ]);
    }
}
