<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\PreOrder;
use App\Services\OrderFulfillmentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PaymentStatus extends Component
{
    public $orderId;

    public ?Order $order = null;
    public ?PreOrder $preOrder = null;
    public $model = null;
    public bool $isPreOrder = false;

    public ?string $payAddress = null;
    public ?string $payCurrency = null;
    public ?float  $payAmount = null;
    public ?float  $priceAmount = null;
    public ?string $priceCurrency = null;
    public ?string $paymentId = null;
    public ?string $network = null;
    public ?string $paymentStatusText = null;
    public ?int    $timeLimit = null;

    public function mount($orderId): void
    {
        $this->orderId = $orderId;
        $this->resolveEntity();
        $this->refreshPaymentStatus();
    }

    private function resolveEntity(): void
    {
        $this->order = Order::find($this->orderId);
        if ($this->order) {
            $this->isPreOrder = false;
            $this->model      = $this->order;
            $this->preOrder   = null;
            return;
        }

        $this->preOrder = PreOrder::find($this->orderId);
        if ($this->preOrder) {
            $this->isPreOrder = true;
            $this->model      = $this->preOrder;
            $this->order      = null;
        }
    }

    public function refreshPaymentStatus(): void
    {
        $this->resolveEntity();
        if (!$this->model) return;

        $this->mapStoredPaymentDetails();

        if ($this->model->payment_status === 'paid') {
            Session::forget('cart');
            return;
        }

        $apiKey    = $this->apiKey();
        $paymentId = $this->model->nowpayments_payment_id ?? null;
        $invoiceId = $this->model->nowpayments_invoice_id ?? null;

        if (!$apiKey || (blank($invoiceId) && blank($paymentId))) return;

        try {
            $url = $this->apiBase() . (filled($paymentId)
                ? '/payment/' . $paymentId
                : '/invoice/' . $invoiceId);

            $response = Http::withHeaders(['x-api-key' => $apiKey])->timeout(10)->get($url);

            if (!$response->successful()) {
                Log::warning('NOWPayments status sync failed.', [
                    'order_id'        => $this->orderId,
                    'order_number'    => $this->model->order_number ?? null,
                    'is_pre_order'    => $this->isPreOrder,
                    'response_status' => $response->status(),
                    'response_body'   => $response->body(),
                ]);
                return;
            }

            $paymentData  = $response->json();
            $remoteStatus = strtolower((string) (
                $paymentData['payment_status']
                ?? $paymentData['order_status']
                ?? $paymentData['invoice_status']
                ?? ''
            ));

            $this->paymentStatusText = $remoteStatus;
            $this->payAddress        = $paymentData['pay_address']    ?? $this->payAddress;
            $this->payCurrency       = $paymentData['pay_currency']   ?? $this->payCurrency;
            $this->payAmount         = isset($paymentData['pay_amount'])   ? (float) $paymentData['pay_amount']   : $this->payAmount;
            $this->priceAmount       = isset($paymentData['price_amount']) ? (float) $paymentData['price_amount'] : $this->priceAmount;
            $this->priceCurrency     = $paymentData['price_currency'] ?? $this->priceCurrency;
            $this->paymentId         = (string) ($paymentData['payment_id'] ?? $paymentId);
            $this->network           = $paymentData['network']        ?? $this->network;
            $this->timeLimit         = isset($paymentData['time_limit']) ? (int) $paymentData['time_limit'] : $this->timeLimit;

            if ($remoteStatus === 'finished') {
                $this->fulfillFromRemote($paymentData);
            }
        } catch (\Throwable $e) {
            Log::error('NOWPayments payment page sync error.', [
                'order_id'     => $this->orderId,
                'is_pre_order' => $this->isPreOrder,
                'message'      => $e->getMessage(),
            ]);
        }
    }

    private function fulfillFromRemote(array $paymentData): void
    {
        if (!$this->model) return;

        $expectedPaymentId = (string) ($this->model->nowpayments_payment_id ?? '');
        $remotePaymentId   = (string) ($paymentData['payment_id'] ?? '');

        if ($expectedPaymentId !== '' && $remotePaymentId !== '' && $expectedPaymentId !== $remotePaymentId) {
            Log::warning('PaymentStatus: payment_id mismatch', [
                'order_id' => $this->orderId,
                'expected' => $expectedPaymentId,
                'received' => $remotePaymentId,
            ]);
            return;
        }

        DB::transaction(function () use ($paymentData) {
            if ($this->isPreOrder && $this->preOrder) {
                if ($this->preOrder->payment_status !== 'paid') {
                    $this->preOrder->update(['payment_status' => 'paid', 'status' => 'processing']);
                }
                return;
            }

            if (!$this->order) return;

            $order = Order::whereKey($this->order->id)->lockForUpdate()->first();
            if (!$order || $order->payment_status === 'paid') return;

            app(OrderFulfillmentService::class)->fulfillOrder($order, [
                'payment_id'     => $paymentData['payment_id'] ?? $order->nowpayments_payment_id,
                'price_amount'   => $this->priceAmount ?? $order->total_price,
                'price_currency' => $this->priceCurrency ?? 'USD',
                'payment_status' => 'finished',
                'pay_address'    => $this->payAddress,
                'pay_currency'   => $this->payCurrency,
                'pay_amount'     => $this->payAmount,
            ]);

            $this->order = $order->fresh();
            $this->model = $this->order;
        });

        Session::forget('cart');
    }

    private function mapStoredPaymentDetails(): void
    {
        if (!$this->model) return;

        $this->payAddress    = $this->model->pay_address  ?? $this->payAddress;
        $this->payCurrency   = $this->model->pay_currency ?? $this->payCurrency;
        $this->payAmount     = $this->model->pay_amount   ?? $this->payAmount;
        $this->priceAmount   = $this->model->total_price  ?? $this->priceAmount;
        $this->priceCurrency = 'USD';
        $this->paymentId     = (string) ($this->model->nowpayments_payment_id ?? $this->paymentId ?? '');
    }

    private function isTestMode(): bool
    {
        return filter_var(config('services.payment.test_mode', false), FILTER_VALIDATE_BOOLEAN);
    }

    private function apiKey(): ?string
    {
        return $this->isTestMode()
            ? config('services.payment.sandbox_api_key')
            : config('services.payment.api_key');
    }

    private function apiBase(): string
    {
        return $this->isTestMode()
            ? 'https://api-sandbox.nowpayments.io/v1'
            : 'https://api.nowpayments.io/v1';
    }

    public function getCurrencyLabel(): string
    {
        return strtoupper((string) $this->payCurrency);
    }

    public function getCryptoUri(): string
    {
        $addr     = trim((string) $this->payAddress);
        $currency = strtolower((string) $this->payCurrency);
        if ($addr === '') return '';

        $amount = $this->payAmount;

        return match ($currency) {
            'btc'  => 'bitcoin:'  . $addr . ($amount ? "?amount={$amount}" : ''),
            'ltc'  => 'litecoin:' . $addr . ($amount ? "?amount={$amount}" : ''),
            'eth', 'usdterc20', 'usdcerc20', 'busderc20',
            'usdtbsc', 'usdcbsc', 'busdbsc', 'bnbbsc', 'bnb'
                   => 'ethereum:' . $addr . ($amount ? "?value={$amount}" : ''),
            'trx', 'usdttrc20', 'usdctrc20'
                   => 'tron:'     . $addr . ($amount ? "?amount={$amount}" : ''),
            'sol'  => 'solana:'   . $addr . ($amount ? "?amount={$amount}" : ''),
            'zec'  => 'zcash:'    . $addr . ($amount ? "?amount={$amount}" : ''),
            default => $addr,
        };
    }

    public function getQrPayload(): string
    {
        return $this->getCryptoUri() ?: (string) $this->payAddress;
    }

    public function getHumanStatus(): string
    {
        if ($this->model?->payment_status === 'paid')   return 'paid';
        if ($this->model?->payment_status === 'failed') return 'failed';

        $remote = strtolower((string) $this->paymentStatusText);

        return match ($remote) {
            'finished'       => 'paid',
            'failed',
            'refunded'       => 'failed',
            'expired'        => 'expired',
            'partially_paid' => 'partially_paid',
            'confirming'     => 'confirming',
            'confirmed'      => 'confirmed',
            'sending'        => 'sending',
            'waiting',
            'pending'        => 'waiting',
            default          => (string) ($this->model?->payment_status ?: 'pending'),
        };
    }

    public function render()
    {
        return view('livewire.payment-status', [
            'entity'      => $this->model,
            'order'       => $this->order,
            'preOrder'    => $this->preOrder,
            'isPreOrder'  => $this->isPreOrder,
            'statusHuman' => $this->getHumanStatus(),
        ]);
    }
}
