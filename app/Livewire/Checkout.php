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

#[Layout('layouts.app')] // ✅ Move this here, on the class
class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $name;
    public $email;
    public $number;
    public $acceptedTerms = false;

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

    public function proceedToPayment()
    {
        $this->validate();

        if (empty($this->cartItems)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }

        session()->put('checkout_data', [
            'name'   => $this->name,
            'email'  => $this->email,
            'number' => $this->number,
        ]);

        DB::beginTransaction();

        try {
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
            ]);

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
                        'order_item_id' => $orderItem->id,
                    ]);
                }

                Storage::disk('public')->makeDirectory('downloads');
                $file = "downloads/order_item_{$orderItem->id}.xlsx";
                Excel::store(new AccountsExport($accounts), $file, 'public');
                $orderItem->update(['download_file' => $file]);

                $product->decrement('stock', $item['quantity']);
            }

            if (config('services.payment.test_mode')) {
                DB::commit();
                Session::forget('cart');
                 $this->dispatch('order-success', [
                'message' => 'Order placed in test mode!',
                'redirect' => route('user.orders')
            ]);
                return;
            }

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
            $this->dispatchBrowserEvent('redirect-to-payment', [
                'url' => $data['invoice_url']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->addError('checkout', "Error: {$e->getMessage()}");
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
