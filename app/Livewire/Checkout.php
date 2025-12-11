<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductAccount;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccountsExport;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;

    public $name;
    public $email;
    public $number;
    public $acceptedTerms = false;

    // Popup fields
    public $showPaymentModal = false;
    public $orderIdInput;
    public $transactionIdInput;

    public function mount()
    {
        if (auth()->check()) {
            $this->name   = auth()->user()->name;
            $this->email  = auth()->user()->email;
            $this->number = auth()->user()->phone;
        }

        $this->cartItems = Session::get('cart', []);
        $this->total = collect($this->cartItems)->sum(fn($i) => $i['price'] * $i['quantity']);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'number' => 'required|string|max:50',
            'acceptedTerms' => 'accepted',
        ];
    }

    // STEP 1 → User clicks "Proceed to Pay" → Show popup only
    public function proceedToPayment()
    {
        $this->validate();

        if (empty($this->cartItems)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }

        // Just open popup – do NOT create order here
        $this->showPaymentModal = true;
    }

    // STEP 2 → User clicks "Confirm Payment" → Create order here
    public function confirmPayment()
    {
        $this->validate([
            'transactionIdInput' => 'required',
        ]);

        DB::beginTransaction();

        try {
            // CREATE ORDER
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => strtoupper(uniqid('ORD-')),
                'total_price' => $this->total,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'ordered_at' => now(),
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->number,
                'transaction_reference' => $this->transactionIdInput,
            ]);

            // ORDER ITEMS AND STOCK CONTROL
            foreach ($this->cartItems as $item) {
                $product = Product::findOrFail($item['id']);

                $accounts = ProductAccount::where('product_id', $product->id)
                    ->where('is_sold', 0)
                    ->limit($item['quantity'])
                    ->get();
                if ($accounts->count() < $item['quantity']) {
                    DB::rollBack();
                    $this->addError('stock', "Not enough stock for {$product->name}");
                    return;
                }

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                foreach ($accounts as $acc) {
                    $acc->update([
                        'is_sold' => 1,
                    ]);
                }

                // Make folder in protected storage
                Storage::makeDirectory('orders');

                // Save the file in storage/app/orders
                $file = "orders/order_item_{$orderItem->id}.xlsx";
                Excel::store(new AccountsExport($accounts), $file); // default disk
                $order->update(['download_file' => $file]);

                // STOCK REDUCE
                $product->decrement('stock', $item['quantity']);
            }

            // TEST MODE → AUTO COMPLETE ORDER
            if (config('services.payment.test_mode')) {

                DB::commit();
                Session::forget('cart');

                $this->showPaymentModal = false;

                $this->dispatch('order-success', message: 'Order placed successfully!', redirect: route('user.orders'));

                return;
            }

            // REAL PAYMENT → NOWPAYMENTS
            $ngrokUrl = config('app.ngrok_url') ?? 'https://your-ngrok-url.ngrok-free.app';

            $response = Http::withHeaders([
                'x-api-key' => config('services.payment.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.nowpayments.io/v1/invoice', [
                "price_amount" => number_format($order->total_price, 2, '.', ''),
                "price_currency" => "usd",
                "order_id" => $order->order_number,
                "order_description" => "Order {$order->order_number}",
                "success_url" => "{$ngrokUrl}/payment/success/{$order->id}",
                "cancel_url" => "{$ngrokUrl}/payment/cancel/{$order->id}",
                "ipn_callback_url" => "{$ngrokUrl}/api/payment/callback",
                "payout_currency" => "usdt",
            ]);

            $data = $response->json();

            if (!$response->successful() || empty($data['invoice_url'])) {
                DB::rollBack();
                Log::error('NowPayments failed', $data);
                $this->addError('payment', $data['message'] ?? 'Payment initialization failed.');
                return;
            }

            DB::commit();
            Session::forget('cart');

            $this->showPaymentModal = false;

            $this->dispatchBrowserEvent('redirect-to-payment', [
                'url' => $data['invoice_url']
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->addError('checkout', "Error: {$e->getMessage()}");
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
