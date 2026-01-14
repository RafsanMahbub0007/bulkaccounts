<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Livewire\Attributes\Layout;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;

#[Layout('layouts.app')]
class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;

    public $name;
    public $email;
    public $number;
    public $acceptedTerms = false;

    // Payment modal
    public $showPaymentModal = false;
    public $transactionIdInput;

    public function mount()
    {
        if (auth()->check()) {
            $this->name   = auth()->user()->name;
            $this->email  = auth()->user()->email;
            $this->number = auth()->user()->phone;
        }

        // Ensure $cartItems is always an array
        $this->cartItems = Session::get('cart', []) ?: [];
        $this->total = collect($this->cartItems)
            ->sum(fn($i) => $i['price'] * $i['quantity']);
    }


    protected function rules()
    {
        return [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email',
            'number'        => 'required|string|max:50',
            'acceptedTerms' => 'accepted',
        ];
    }

    public function getIsTestModeProperty()
    {
        return config('services.payment.test_mode');
    }

    /* ================= SHOW PAYMENT MODAL ================= */
    public function proceedToPayment()
    {
        $this->validate();

        if (empty($this->cartItems)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }

        $this->showPaymentModal = true;
    }

    /* ================= CONFIRM PAYMENT ================= */
    public function confirmPayment()
    {
        // Only require transaction ID in test mode
        $this->validate([
            'transactionIdInput' => $this->isTestMode ? 'required|string' : 'nullable',
        ]);

        if (empty($this->cartItems)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }

        DB::beginTransaction();

        try {
            /* ================= CREATE ORDER ================= */
            $order = Order::create([
                'user_id'      => auth()->id(),
                'order_number' => strtoupper(uniqid('ORD-')),
                'total_price'  => $this->total,
                'payment_status' => 'unpaid',
                'order_status'   => 'pending',
                'ordered_at'     => now(),
                'name'  => $this->name,
                'email' => $this->email,
                'phone' => $this->number,
            ]);
            /* ================= CREATE ORDER ITEMS ================= */
            foreach ($this->cartItems as $item) {
                $product = Product::findOrFail($item['id']);

                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $product->id,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            $this->showPaymentModal = false;
            // dd($this->cartItems);

            /* ================= TEST MODE (MANUAL PAYMENT) ================= */
            if ($this->isTestMode) {
                // Create a pending payment record for admin review
                \App\Models\Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => $this->transactionIdInput,
                    'amount' => $order->total_price,
                    'currency' => 'USD',
                    'status' => 'pending', // Pending admin approval
                ]);

                // Just clear cart and redirect. 
                // Admin will manually fulfill the order.
                Session::forget('cart');
                return redirect()->route('home')->with('success', 'Order placed successfully! Waiting for admin approval.');
            }

            /* ================= LIVE PAYMENT ================= */
            $response = Http::withHeaders([
                'x-api-key'    => config('services.payment.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.nowpayments.io/v1/invoice', [
                'price_amount'     => number_format($order->total_price, 2, '.', ''),
                'price_currency'   => 'USD',
                'order_id'         => $order->order_number,
                'order_description' => "Order {$order->order_number}",
                'success_url'      => route('payment.status', $order->id),
                'cancel_url'       => route('payment.status', $order->id),
                'ipn_callback_url' => route('payment.callback'),
                'payout_currency'  => 'USDTTRC20',
            ]);

            $data = $response->json();

            if (!$response->successful() || empty($data['invoice_url'])) {
                throw new \Exception('Payment initialization failed.');
            }
            Session::forget('cart');
            return redirect()->away($data['invoice_url']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout error', ['message' => $e->getMessage()]);
            $this->addError('checkout', $e->getMessage());
        }
    }

    public function render()
    {
        $system = Setting::find(1);

        return view('livewire.checkout', [
            'system'     => $system,
            'isTestMode' => $this->isTestMode,
            'cartItems'  => $this->cartItems,
            'total'      => $this->total,
        ]);
    }
}
