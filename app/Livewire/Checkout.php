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
use App\Models\Setting;
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
    public $transactionIdInput;
    public $manualPayment = false;

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

    public function getIsTestModeProperty()
    {
        return config('services.payment.test_mode');
    }

    /**
     * Show the payment modal
     */
    public function proceedToPayment($manual = false)
    {
        $this->validate();

        if (empty($this->cartItems)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }

        $this->manualPayment = $manual && $this->isTestMode; // Only allow manual in test mode
        $this->showPaymentModal = true;
    }

    /**
     * Confirm the payment (manual or live)
     */
    public function confirmPayment()
    {
        // 1️⃣ Validate only if manual payment
        $this->validate([
            'transactionIdInput' => $this->manualPayment ? 'required|string' : 'nullable',
        ]);

        if (empty($this->cartItems)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }

        DB::beginTransaction();

        try {
            // 2️⃣ Create the order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => strtoupper(uniqid('ORD-')),
                'total_price' => $this->total,
                'payment_status' => $this->manualPayment ? 'paid' : 'unpaid',
                'order_status' => 'pending',
                'ordered_at' => now(),
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->number,
                'transaction_reference' => $this->manualPayment ? $this->transactionIdInput : null,
            ]);

            // 3️⃣ Handle order items and stock
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
                    $acc->update(['is_sold' => 1]);
                }

                Storage::makeDirectory('orders');
                $file = "orders/order_item_{$orderItem->id}.xlsx";
                Excel::store(new AccountsExport($accounts), $file);
                $order->update(['download_file' => $file]);

                $product->decrement('stock', $item['quantity']);
            }

            // 4️⃣ Manual Payment (Test Mode)
            if ($this->manualPayment) {
                DB::commit();
                Session::forget('cart');
                $this->showPaymentModal = false;

                $this->emit('order-success', [
                    'message' => 'Manual payment recorded successfully!',
                    'redirect' => route('user.orders')
                ]);
                return;
            }

            // 5️⃣ Live Payment (NowPayments)
            if ($this->isTestMode) {

                $response = Http::withOptions([
    'verify' => false, // Disable SSL certificate validation
])->withHeaders([
    'x-api-key' => config('services.payment.api_key'),
    'Content-Type' => 'application/json',
])->post('https://api-sandbox.nowpayments.io/v1/invoice', [
    "price_amount" => number_format($order->total_price, 2, '.', ''),
    "price_currency" => "USD",
    "order_id" => $order->order_number,
    "order_description" => "Order {$order->order_number}",
    "success_url" => route('payment.success', $order->id),
    "cancel_url" => route('payment.cancel', $order->id),
    "ipn_callback_url" => "https://nonconsequent-hollis-unpliant.ngrok-free.dev/payment/callback",
    "payout_currency" => "USDTTRC20",
]);
                $data = $response->json();

                if (!$response->successful() || empty($data['invoice_url'])) {
                    DB::rollBack();
                    Log::error('NowPayments failed', $data);
                    $this->addError('payment', 'Payment initialization failed.');
                    return;
                }

                DB::commit();
                Session::forget('cart');
                $this->showPaymentModal = false;

                // ✅ Livewire 3 compatible redirect using emit()
                return redirect()->away($data['invoice_url']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error', [
                'message' => $e->getMessage(),
            ]);
            $this->addError('checkout', $e->getMessage());
        }
    }



    public function render()
    {
        $system = Setting::find(1);
        return view('livewire.checkout', [
            'system' => $system,
            'isTestMode' => $this->isTestMode,
        ]);
    }
}
