<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\PreOrder;
use App\Services\OrderFulfillmentService;

class PaymentController extends Controller
{
    public function handle(Request $request)
    {
        $rawBody = $request->getContent();
        $data    = json_decode($rawBody, true);

        if (!is_array($data)) {
            $data = $request->all();
        }

        // 1. Verify HMAC signature from NOWPayments (uses sandbox vs live secret)
        if (!$this->verifySignature($rawBody, $request->header('x-nowpayments-sig'))) {
            Log::warning('NOWPayments IPN signature mismatch', [
                'ip'        => $request->ip(),
                'test_mode' => $this->isTestMode(),
            ]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // 2. Validate payload
        if (!isset($data['order_id'], $data['payment_status'])) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        Log::info('NOWPayments IPN received', [
            'ip'             => $request->ip(),
            'order_id'       => $data['order_id'],
            'payment_id'     => $data['payment_id'] ?? null,
            'payment_status' => $data['payment_status'],
            'test_mode'      => $this->isTestMode(),
        ]);

        $orderNumber = $data['order_id'];

        return DB::transaction(function () use ($data, $orderNumber) {
            // 3. Row-locked lookups
            $order    = Order::where('order_number', $orderNumber)->lockForUpdate()->first();
            $preOrder = PreOrder::where('order_number', $orderNumber)->lockForUpdate()->first();

            if (!$order && !$preOrder) {
                Log::warning('IPN: order not found', ['order_number' => $orderNumber]);
                return response()->json(['status' => 'order_not_found']);
            }

            // 4. Idempotency — never reprocess a paid order
            if (($order && $order->payment_status === 'paid')
                || ($preOrder && $preOrder->payment_status === 'paid')) {
                return response()->json(['status' => 'already_processed']);
            }

            // 5. Verify payment_id matches — STRING-TO-STRING (the fix)
            $expectedPaymentId = (string) ($order->nowpayments_payment_id
                ?? $preOrder->nowpayments_payment_id
                ?? '');

            $receivedPaymentId = (string) ($data['payment_id'] ?? '');

            if (
                $expectedPaymentId !== ''
                && $receivedPaymentId !== ''
                && $expectedPaymentId !== $receivedPaymentId
            ) {
                Log::warning('IPN: payment_id mismatch', [
                    'order_number' => $orderNumber,
                    'expected'     => $expectedPaymentId,
                    'received'     => $receivedPaymentId,
                ]);
                return response()->json(['error' => 'Payment ID mismatch'], 403);
            }

            // 6. Currency check
            if ($order && $order->pay_currency
                && isset($data['pay_currency'])
                && $data['pay_currency'] !== $order->pay_currency) {
                Log::warning('IPN: pay_currency mismatch', [
                    'order_number' => $orderNumber,
                    'expected'     => $order->pay_currency,
                    'received'     => $data['pay_currency'],
                ]);
                return response()->json(['error' => 'Currency mismatch'], 403);
            }

            // 7. Price check (1% tolerance)
            if ($order && isset($data['price_amount'])
                && (float) $data['price_amount'] < (float) $order->total_price * 0.99) {
                Log::warning('IPN: price mismatch', [
                    'order_number' => $orderNumber,
                    'expected'     => $order->total_price,
                    'received'     => $data['price_amount'],
                ]);
                return response()->json(['error' => 'Price mismatch'], 403);
            }

            // 8. Record non-final statuses (waiting, confirming, failed, etc.)
            if ($data['payment_status'] !== 'finished') {
                $this->recordStatus($order, $preOrder, $data['payment_status']);

                Log::info('IPN: non-final status, ignoring fulfillment', [
                    'order_number' => $orderNumber,
                    'status'       => $data['payment_status'],
                ]);
                return response()->json(['status' => 'ok']);
            }

            // 9. Underpayment check (1% tolerance)
            if ($order && $order->pay_amount && isset($data['actually_paid'])
                && (float) $data['actually_paid'] < (float) $order->pay_amount * 0.99) {
                Log::warning('IPN: underpaid', [
                    'order_number' => $orderNumber,
                    'expected'     => $order->pay_amount,
                    'paid'         => $data['actually_paid'],
                ]);
                $order->update(['payment_status' => 'underpaid']);
                return response()->json(['status' => 'underpaid']);
            }

            // 10. Fulfill
            if ($order) {
                app(OrderFulfillmentService::class)->fulfillOrder($order, $data);
            }

            if ($preOrder) {
                $preOrder->update([
                    'payment_status' => 'paid',
                    'status'         => 'processing',
                ]);
            }

            Log::info('IPN: fulfillment complete', ['order_number' => $orderNumber]);

            return response()->json(['status' => 'ok']);
        });
    }

    /**
     * Record non-final payment statuses on the order for audit trail.
     */
    private function recordStatus(?Order $order, ?PreOrder $preOrder, string $status): void
    {
        $mapped = match ($status) {
            'partially_paid'    => 'underpaid',
            'failed', 'expired' => 'failed',
            'refunded'          => 'refunded',
            default             => null,
        };

        if (!$mapped) return;

        $order?->update(['payment_status' => $mapped]);
        $preOrder?->update(['payment_status' => $mapped]);
    }

    /**
     * Verify HMAC-SHA512 signature from NOWPayments.
     * Uses the sandbox secret when PAYMENT_TEST_MODE=true,
     * otherwise the live secret.
     */
    private function verifySignature(string $rawBody, ?string $receivedSig): bool
    {
        $secret = $this->isTestMode()
            ? config('services.payment.sandbox_secret_key')
            : config('services.payment.secret_key');

        if (!$secret || !$receivedSig) {
            Log::warning('IPN verification skipped: missing secret or signature', [
                'has_secret' => (bool) $secret,
                'has_sig'    => (bool) $receivedSig,
                'test_mode'  => $this->isTestMode(),
            ]);
            return false;
        }

        $data = json_decode($rawBody, true);
        if (!is_array($data)) return false;

        // NOWPayments requires keys sorted alphabetically before hashing
        ksort($data);

        $sortedJson = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $computed   = hash_hmac('sha512', $sortedJson, trim($secret));

        return hash_equals($computed, $receivedSig);
    }

    private function isTestMode(): bool
    {
        return filter_var(
            config('services.payment.test_mode', false),
            FILTER_VALIDATE_BOOLEAN
        );
    }
}
