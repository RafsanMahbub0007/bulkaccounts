<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\{Session, Http, DB, Log, Cache, Schema};
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\{Product, Order, OrderItem, PreOrder, PreOrderItem, Setting, Payment};

#[Layout('layouts.app')]
class Checkout extends Component
{
    public $cartItems = [];
    public $total = 0;

    public $name, $email;
    public $countryCode = '+1';
    public $number;
    public $paymentMethod = 'usdt_trc20';
    public $acceptedTerms = false;

    public $showPaymentModal = false;
    public $transactionIdInput;

    private static array $paymentCurrencies = [
        'tron'       => ['label' => 'TRON (TRX)',      'pay_currency' => 'trx',       'outcome_currency' => 'trx'],
        'btc'        => ['label' => 'Bitcoin (BTC)',   'pay_currency' => 'btc',       'outcome_currency' => 'btc'],
        'eth'        => ['label' => 'Ethereum (ETH)',  'pay_currency' => 'eth',       'outcome_currency' => 'eth'],
        'ltc'        => ['label' => 'Litecoin (LTC)',  'pay_currency' => 'ltc',       'outcome_currency' => 'ltc'],
        'bnb_bsc'    => ['label' => 'BNB (BSC)',       'pay_currency' => 'bnbbsc',    'outcome_currency' => 'bnbbsc'],
        'usdt_trc20' => ['label' => 'USDT (TRC20)',    'pay_currency' => 'usdttrc20', 'outcome_currency' => 'usdttrc20'],
        'busd_erc20' => ['label' => 'BUSD (ERC20)',    'pay_currency' => 'busderc20', 'outcome_currency' => 'busderc20'],
        'busd_bsc'   => ['label' => 'BUSD (BSC)',      'pay_currency' => 'busdbsc',   'outcome_currency' => 'busdbsc'],
        'sol'        => ['label' => 'Solana (SOL)',    'pay_currency' => 'sol',       'outcome_currency' => 'sol'],
    ];

    public function mount(): void
    {
        if (auth()->check()) {
            $this->name  = auth()->user()->name;
            $this->email = auth()->user()->email;
            $this->setPhoneFromStored(auth()->user()->phone);
        }

        $this->cartItems = Session::get('cart', []) ?: [];
        $this->total = $this->calcTotal($this->cartItems);
    }

    protected function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email',
            'countryCode'   => ['required', 'string', Rule::in($this->dialCodes())],
            'number'        => ['required', 'string', 'max:50', function ($attr, $val, $fail) {
                if (strlen(preg_replace('/\D/', '', (string) $val)) < 5) {
                    $fail('Phone number must contain at least 5 digits.');
                }
            }],
            'paymentMethod' => ['required', 'string', Rule::in(array_keys(self::$paymentCurrencies))],
            'acceptedTerms' => 'accepted',
        ];
    }

    public function paymentMethodOptions(): array
    {
        $options = [];
        foreach (self::$paymentCurrencies as $key => $config) {
            $min = $this->fetchMinimumAmount($key);
            $options[$key] = $min !== null
                ? sprintf('%s (min. $%s)', $config['label'], number_format($min, 2))
                : $config['label'];
        }
        return $options;
    }

    private function paymentMethodLabel(): string
    {
        return self::$paymentCurrencies[$this->paymentMethod]['label'] ?? $this->paymentMethod;
    }

    private function getPaymentCurrencyConfig(): ?array
    {
        return self::$paymentCurrencies[$this->paymentMethod] ?? null;
    }

    private function fetchMinimumAmount(string $paymentMethod, bool $forceRefresh = false): ?float
    {
        if ($this->isTestMode) return null;

        $config = self::$paymentCurrencies[$paymentMethod] ?? null;
        $apiKey = config('services.payment.api_key');

        if (!$config || !$apiKey) {
            if (!$apiKey) Log::warning('NOWPayments API key missing.', ['method' => $paymentMethod]);
            return null;
        }

        $cacheKey = 'nowpayments:min-amount:' . strtolower($config['pay_currency']) . ':' . strtolower($config['outcome_currency']);

        if (!$forceRefresh && is_numeric($cached = Cache::get($cacheKey))) {
            return (float) $cached;
        }

        try {
            $response = Http::acceptJson()
                ->withHeaders(['x-api-key' => $apiKey])
                ->timeout(8)
                ->get($this->nowPaymentsApiBase() . '/min-amount', [
                    'currency_from'       => $config['pay_currency'],
                    'currency_to'         => $config['outcome_currency'],
                    'fiat_equivalent'     => 'usd',
                    'is_fixed_rate'       => 'false',
                    'is_fee_paid_by_user' => 'false',
                ]);

            if (!$response->successful()) {
                Log::warning('NOWPayments min-amount request failed.', [
                    'method' => $paymentMethod,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();
            $minimumUsd = is_numeric($data['fiat_equivalent'] ?? null) ? (float) $data['fiat_equivalent'] : null;

            if ($minimumUsd === null && is_numeric($data['min_amount'] ?? null) && (float) $data['min_amount'] > 0) {
                $estimate = Http::acceptJson()
                    ->withHeaders(['x-api-key' => $apiKey])
                    ->timeout(8)
                    ->get($this->nowPaymentsApiBase() . '/estimate', [
                        'amount'        => (float) $data['min_amount'],
                        'currency_from' => $config['pay_currency'],
                        'currency_to'   => 'usd',
                    ]);

                if ($estimate->successful() && is_numeric($estimate->json()['estimated_amount'] ?? null)) {
                    $minimumUsd = (float) $estimate->json()['estimated_amount'];
                }
            }

            if ($minimumUsd === null || $minimumUsd < 0) {
                Log::warning('NOWPayments returned no usable USD minimum.', ['method' => $paymentMethod, 'response' => $data]);
                return null;
            }

            Cache::put($cacheKey, $minimumUsd, now()->addSeconds(60));
            return $minimumUsd;
        } catch (\Throwable $e) {
            Log::warning('NOWPayments min amount fetch failed.', ['method' => $paymentMethod, 'message' => $e->getMessage()]);
            return null;
        }
    }

   public function getIsTestModeProperty(): bool
{
    return filter_var(
        config('services.payment.test_mode'),
        FILTER_VALIDATE_BOOLEAN
    );
}

    private function nowPaymentsApiBase(): string
    {
        return $this->isTestMode
            ? 'https://api-sandbox.nowpayments.io/v1'
            : 'https://api-sandbox.nowpayments.io/v1';
    }

    public function proceedToPayment(): void
    {
        if (empty($this->cartItems)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }

        $this->validate();

        if (!$this->isTestMode) {
            $config = $this->getPaymentCurrencyConfig();
            if (!$config) {
                $this->addError('paymentMethod', 'Please select a supported cryptocurrency.');
                return;
            }

            $minimum = $this->fetchMinimumAmount($this->paymentMethod, true);

            if ($minimum === null) {
                $this->addError('paymentMethod', 'We could not verify the current minimum payment. Please try again.');
                return;
            }

            if ((float) $this->total < $minimum) {
                $this->addError('paymentMethod', sprintf(
                    'The current minimum order for %s is $%s. Your order total is $%s. Please add more items or choose another cryptocurrency.',
                    $config['label'],
                    number_format($minimum, 2),
                    number_format((float) $this->total, 2)
                ));
                return;
            }
        }

        $this->showPaymentModal = true;
    }

    public function confirmPayment()
    {
        $this->validate(['transactionIdInput' => $this->isTestMode ? 'required|string' : 'nullable']);

        if (empty($this->cartItems)) {
            $this->addError('cart', 'Your cart is empty.');
            return;
        }

        DB::beginTransaction();

        try {
            $this->total = $this->calcTotal($this->cartItems);

            if (!$this->isTestMode) {
                $config = $this->getPaymentCurrencyConfig() ?? throw new \Exception('Please select a supported cryptocurrency.');
                $minimum = $this->fetchMinimumAmount($this->paymentMethod, true)
                    ?? throw new \Exception('NOWPayments minimum payment could not be verified. Please try again.');

                if ((float) $this->total < $minimum) {
                    throw new \Exception(sprintf(
                        'The minimum for %s is currently $%s. Your order total is $%s.',
                        $config['label'],
                        number_format($minimum, 2),
                        number_format((float) $this->total, 2)
                    ));
                }
            }

            [$regularItems, $preOrderItems] = $this->splitCartItems($this->cartItems);
            $orderNumber = strtoupper(uniqid('ORD-'));
            $orderIdForPayment = null;

            if (!empty($regularItems)) {
                $order = $this->createOrder($orderNumber, $this->calcTotal($regularItems), 'orders');
                $this->createItems($order->id, $regularItems, OrderItem::class, 'order_id');
                $orderIdForPayment = $order->id;
            }

            if (!empty($preOrderItems)) {
                $preOrder = $this->createPreOrder($orderNumber, $this->calcTotal($preOrderItems));
                $this->createItems($preOrder->id, $preOrderItems, PreOrderItem::class, 'pre_order_id');
                $orderIdForPayment ??= $preOrder->id;
            }

            if (!$this->isTestMode) {
                $this->processLivePayment($orderNumber, $order ?? null, $preOrder ?? null);
                DB::commit();
                $this->showPaymentModal = false;
                Session::forget('cart');

                return redirect()->route('payment.status', ['orderId' => $orderIdForPayment]);
            }

            if (isset($order)) {
                Payment::create([
                    'order_id'       => $order->id,
                    'transaction_id' => $this->transactionIdInput,
                    'amount'         => $this->total,
                    'currency'       => 'USD',
                    'status'         => 'pending',
                    'payment_method' => $this->paymentMethodLabel(),
                ]);
            }

            DB::commit();
            Session::forget('cart');

            return redirect()->route('home')->with(
                'success',
                'Order placed successfully! Delivery of pre-order items will take 24-72 hours.'
            );
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) DB::rollBack();

            Log::error('Checkout Error', ['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
            $this->addError('checkout', $e->getMessage());
        }
    }

    private function processLivePayment(string $orderNumber, ?Order $order, ?PreOrder $preOrder): void
    {
        $apiKey = config('services.payment.api_key') ?? throw new \Exception('Payment gateway API key is not configured.');
        $config = $this->getPaymentCurrencyConfig() ?? throw new \Exception('Please select a supported cryptocurrency.');
        $payCurrency = $config['pay_currency'];

        $response = Http::acceptJson()
            ->withHeaders(['x-api-key' => $apiKey, 'Content-Type' => 'application/json'])
            ->timeout(20)
            ->post($this->nowPaymentsApiBase() . '/payment', [
                'price_amount'        => round((float) $this->total, 2),
                'price_currency'      => 'usd',
                'pay_currency'        => $payCurrency,
                'order_id'            => $orderNumber,
                'order_description'   => "Order {$orderNumber}",
                'ipn_callback_url'    => route('payment.callback'),
                'is_fixed_rate'       => false,
                'is_fee_paid_by_user' => false,
            ]);

        $data = $response->json();

        if (!$response->successful()) {
            Log::error('NOWPayments Payment Error', [
                'status' => $response->status(),
                'response' => $data,
                'body' => $response->body(),
                'pay_currency' => $payCurrency,
                'order_number' => $orderNumber,
            ]);
            throw new \Exception($data['message'] ?? $data['error'] ?? 'Payment initialization failed.');
        }

        if (empty($data['payment_id'])) {
            throw new \Exception('NOWPayments returned no payment ID.');
        }

        $paymentId  = $data['payment_id'];
        $payAddress = $data['pay_address'] ?? null;
        $payAmount  = isset($data['pay_amount']) ? (float) $data['pay_amount'] : null;

        $updates = [
            'nowpayments_payment_id' => $paymentId,
            'pay_currency'           => $payCurrency,
        ];

        if (!empty($data['invoice_id'])) $updates['nowpayments_invoice_id'] = $data['invoice_id'];
        if ($payAddress)                  $updates['pay_address']            = $payAddress;
        if ($payAmount !== null)          $updates['pay_amount']             = $payAmount;

        foreach ([['model' => $order, 'table' => 'orders'], ['model' => $preOrder, 'table' => 'pre_orders']] as $entry) {
            if (!$entry['model']) continue;

            $filtered = collect($updates)
                ->filter(fn($v, $k) => Schema::hasColumn($entry['table'], $k))
                ->all();

            if ($filtered) $entry['model']->forceFill($filtered)->save();
        }
    }

    private function createOrder(string $orderNumber, float $total, string $table): Order
    {
        $order = new Order();
        $order->user_id        = auth()->id();
        $order->guest_email    = auth()->check() ? null : $this->email;
        $order->order_number   = $orderNumber;
        $order->total_price    = $total;
        $order->payment_status = 'unpaid';
        $order->order_status   = 'pending';
        $order->ordered_at     = now();

        foreach (['name' => $this->name, 'email' => $this->email, 'phone' => $this->fullPhone(), 'payment_method' => $this->paymentMethodLabel()] as $col => $val) {
            if (Schema::hasColumn($table, $col)) $order->{$col} = $val;
        }

        $order->save();
        return $order;
    }

    private function createPreOrder(string $orderNumber, float $total): PreOrder
    {
        $data = [
            'user_id'        => auth()->id(),
            'order_number'   => $orderNumber,
            'total_price'    => $total,
            'payment_status' => 'unpaid',
            'status'         => 'pending',
            'ordered_at'     => now(),
            'name'           => $this->name,
            'email'          => $this->email,
            'phone'          => $this->fullPhone(),
        ];

        if (Schema::hasColumn('pre_orders', 'payment_method')) {
            $data['payment_method'] = $this->paymentMethodLabel();
        }

        return PreOrder::create($data);
    }

    private function createItems(int $parentId, array $items, string $modelClass, string $fk): void
    {
        $products = Product::whereIn('id', collect($items)->pluck('id')->all())->get()->keyBy('id');

        foreach ($items as $item) {
            $product = $products->get($item['id']) ?? throw new \Exception('One or more products were not found.');

            $modelClass::create([
                $fk           => $parentId,
                'product_id'  => $product->id,
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['price'],
                'total_price' => (float) $item['price'] * (int) $item['quantity'],
            ]);
        }
    }

   private function splitCartItems(array $items): array
{
    $regular  = [];
    $preOrder = [];

    foreach ($items as $item) {
        if (!empty($item['is_preorder'])) {
            $preOrder[] = $item;
        } else {
            $regular[] = $item;
        }
    }

    return [$regular, $preOrder];
}

    private function calcTotal(array $items): float
    {
        return (float) collect($items)->sum(fn($i) => (float) $i['price'] * (int) $i['quantity']);
    }

    public function render()
{
    // TEMP DEBUG — remove after fixing
    Log::info('CHECKOUT DEBUG', [
        'test_mode_raw'  => config('services.payment.test_mode'),
        'test_mode_bool' => $this->isTestMode,
        'api_key'        => config('services.payment.api_key') ? 'SET' : 'MISSING',
        'api_key_value'  => substr((string) config('services.payment.api_key'), 0, 8) . '...',
    ]);

    return view('livewire.checkout', [
        'system'         => Setting::find(1),
        'isTestMode'     => $this->isTestMode,
        'cartItems'      => $this->cartItems,
        'total'          => $this->total,
        'countryCodes'   => $this->countryDialOptions(),
        'paymentMethods' => $this->paymentMethodOptions(),
    ]);
}
    // public function render()
    // {
    //     return view('livewire.checkout', [
    //         'system'         => Setting::find(1),
    //         'isTestMode'     => $this->isTestMode,
    //         'cartItems'      => $this->cartItems,
    //         'total'          => $this->total,
    //         'countryCodes'   => $this->countryDialOptions(),
    //         'paymentMethods' => $this->paymentMethodOptions(),
    //     ]);
    // }

    private function setPhoneFromStored(?string $phone): void
    {
        $phone = trim((string) $phone);
        if ($phone === '') return;

        if (Str::startsWith($phone, '+')) {
            foreach ($this->dialCodesSorted() as $code) {
                if (Str::startsWith($phone, $code)) {
                    $this->countryCode = $code;
                    $this->number = trim(substr($phone, strlen($code)));
                    return;
                }
            }
        }

        $this->number = $phone;
    }

    private function fullPhone(): string
    {
        return preg_replace('/\s+/', '', (string) $this->countryCode)
            . preg_replace('/\D/', '', (string) $this->number);
    }

    private function countryDialOptions(): array
    {
        $preferred = ['+1' => 'US', '+44' => 'GB', '+61' => 'AU', '+7' => 'RU', '+91' => 'IN', '+880' => 'BD'];

        return collect(config('country_codes', []))
            ->filter(fn($c) => is_array($c) && isset($c['dial_code'], $c['iso2']))
            ->groupBy('dial_code')
            ->map(function ($group, $dial) use ($preferred) {
                $match = isset($preferred[$dial])
                    ? $group->firstWhere('iso2', $preferred[$dial])
                    : null;

                $first = $match ?? $group->first();

                return [
                    'dial_code' => $first['dial_code'],
                    'iso2'      => $first['iso2'],
                    'name'      => $first['name'] ?? $first['iso2'],
                ];
            })
            ->values()
            ->sortBy('dial_code')
            ->values()
            ->all();
    }

   private function dialCodes(): array
{
    static $codes = null;

    if ($codes !== null) {
        return $codes;
    }

    $codes = collect($this->countryDialOptions())
        ->pluck('dial_code')
        ->filter(fn ($c) => is_string($c) && $c !== '')
        ->unique()
        ->values()
        ->all();

    return $codes;
}

private function dialCodesSorted(): array
{
    static $sorted = null;

    if ($sorted !== null) {
        return $sorted;
    }

    $sorted = $this->dialCodes();
    usort($sorted, fn ($a, $b) => strlen($b) <=> strlen($a));

    return $sorted;
}
}
