<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Order;
use Livewire\Attributes\Validate;

class UserOrderDetails extends Component
{
    public $order;

    // payment
    public $payment_modal;

    #[Validate('required')]
    public $payment_method = '';

    #[Validate('required')]
    public $transaction_id = '';

    #[Validate('required')]
    public $currency = '';

    #[Validate('required')]
    public $amount = '';


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

    public function pay()
    {
        $this->validate();

        try {
            $this->order->payments()->create([
                'payment_method' => $this->payment_method,
                'transaction_id' => $this->transaction_id,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'status' => 'pending',
            ]);

            session()->flash('status', 'Payment successful');
            $this->togglePaymentModal();
        } catch (\Exception $e) {
            session()->flash('error', 'There was an issue processing the payment');
        }
    }


    public function togglePaymentModal()
    {
        $this->payment_modal = !$this->payment_modal;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.user-order-details', [
            'payments' => $this->order->payments, // Pass payments data to the view
        ]);
    }
}
