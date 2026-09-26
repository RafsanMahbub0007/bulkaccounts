<div wire:poll.7s="refreshPaymentStatus" x-data="{ showConfetti: false, copied: '' }" x-init="
    if (@js($statusHuman) === 'paid') {
        showConfetti = true;
        setTimeout(() => showConfetti = false, 3000);
    }
" class="min-h-[60vh]">
    @php
        $entity = $entity ?? $order ?? $preOrder ?? null;

        $isWaiting = in_array($statusHuman, ['pending', 'unpaid', 'waiting', 'confirming', 'confirmed', 'sending'], true);
        $isPaid    = $statusHuman === 'paid';
        $isFailed  = $statusHuman === 'failed';
        $isExpired = $statusHuman === 'expired';
        $isPartial = $statusHuman === 'partially_paid';

        $statusClassMap = [
            'paid'           => 'bg-green-500/20 text-green-400 border-green-500/30',
            'failed'         => 'bg-red-500/20 text-red-400 border-red-500/30',
            'expired'        => 'bg-gray-500/20 text-gray-300 border-gray-500/30',
            'partially_paid' => 'bg-orange-500/15 text-orange-300 border-orange-500/30',
            'pending'        => 'bg-yellow-500/15 text-yellow-300 border-yellow-500/30',
            'unpaid'         => 'bg-yellow-500/15 text-yellow-300 border-yellow-500/30',
            'waiting'        => 'bg-yellow-500/15 text-yellow-300 border-yellow-500/30',
            'confirming'     => 'bg-blue-500/15 text-blue-300 border-blue-500/30',
            'confirmed'      => 'bg-blue-500/15 text-blue-300 border-blue-500/30',
            'sending'        => 'bg-indigo-500/15 text-indigo-300 border-indigo-500/30',
        ];
        $statusLabelMap = [
            'paid'           => 'Payment Confirmed',
            'failed'         => 'Payment Failed',
            'expired'        => 'Payment Expired',
            'partially_paid' => 'Underpaid — Contact Support',
            'pending'        => 'Awaiting your payment',
            'unpaid'         => 'Awaiting your payment',
            'waiting'        => 'Awaiting your payment',
            'confirming'     => 'Confirming on blockchain…',
            'confirmed'      => 'Confirmed — Sending to merchant…',
            'sending'        => 'Sending to merchant…',
        ];
        $statusClass = $statusClassMap[$statusHuman] ?? $statusClassMap['pending'];
        $statusLabel = $statusLabelMap[$statusHuman] ?? $statusLabelMap['pending'];
    @endphp

    @if ($entity)
        <div class="relative mt-5 mb-10 max-w-2xl mx-auto">
            <div class="bg-gray-800/80 backdrop-blur rounded-3xl shadow-2xl border border-gray-700 overflow-hidden">

                <template x-if="showConfetti">
                    <div class="absolute inset-0 pointer-events-none">
                        <div class="w-full h-full relative">
                            <div class="absolute w-2 h-2 bg-yellow-400 rounded-full animate-confetti" style="top:10%; left:20%"></div>
                            <div class="absolute w-2 h-2 bg-pink-400 rounded-full animate-confetti" style="top:15%; left:50%"></div>
                            <div class="absolute w-2 h-2 bg-green-400 rounded-full animate-confetti" style="top:5%; left:70%"></div>
                            <div class="absolute w-2 h-2 bg-blue-400 rounded-full animate-confetti" style="top:20%; left:30%"></div>
                            <div class="absolute w-2 h-2 bg-purple-400 rounded-full animate-confetti" style="top:10%; left:80%"></div>
                        </div>
                    </div>
                </template>

                <div class="bg-gradient-to-r from-gray-900 to-gray-800 px-8 py-6 text-center border-b border-gray-700">
                    <h2 class="text-white text-2xl font-bold tracking-wide">
                        {{ $isPreOrder ? 'Pre-Order' : 'Order' }} #{{ $entity->order_number }}
                    </h2>
                    <p class="text-gray-400 text-sm mt-1">
                        @if ($isPaid)     Your payment has been confirmed. Thank you!
                        @elseif ($isFailed) Something went wrong with this payment.
                        @elseif ($isExpired) This payment window has expired. Please create a new order.
                        @elseif ($isPartial) We received less than the required amount.
                        @else             Send the exact crypto amount below to complete your order.
                        @endif
                    </p>
                </div>

                <div class="p-8 space-y-6">

                    <div class="flex items-center justify-between bg-gray-900/60 rounded-2xl p-4 shadow-inner border border-gray-700/50">
                        <span class="text-gray-300 font-medium">Payment Status</span>
                        <span class="flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                            @if ($isWaiting && !$isPartial)
                                <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                            @endif
                            {{ $statusLabel }}
                        </span>
                    </div>

                    @if ($isWaiting)
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-white p-4 rounded-2xl shadow-inner border border-gray-200 text-center">
                                <div class="inline-block bg-white rounded-xl p-2">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&amp;margin=8&amp;data={{ urlencode($this->getQrPayload()) }}"
                                         alt="Payment QR" class="w-full max-w-[220px] h-auto rounded-lg">
                                </div>
                                <p class="text-gray-600 text-xs mt-2">Scan with any crypto wallet app</p>
                            </div>

                            <div class="space-y-4">
                                <div class="rounded-2xl border border-gray-700 bg-gray-900/60 p-4">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-gray-400 text-xs uppercase tracking-wider">Order total</span>
                                        <span class="text-white font-bold text-2xl">
                                            ${{ number_format((float) ($priceAmount ?? $entity->total_price), 2) }}
                                            <span class="text-gray-400 text-xs font-normal ml-1">{{ strtoupper($priceCurrency ?? 'USD') }}</span>
                                        </span>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-yellow-500/40 bg-yellow-500/5 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-gray-400 text-xs uppercase tracking-wider">Send exactly</p>
                                            <p class="mt-1 text-yellow-300 font-mono font-bold text-2xl break-all">
                                                {{ number_format((float) ($payAmount ?? 0), 8) }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="px-2 py-0.5 rounded-md bg-yellow-500/15 border border-yellow-500/30 text-yellow-200 text-[10px] font-semibold uppercase tracking-wider">
                                                {{ $this->getCurrencyLabel() }}
                                            </span>
                                            @if (!empty($network))
                                                <span class="px-2 py-0.5 rounded-md bg-gray-700/70 text-gray-300 text-[10px] font-semibold uppercase tracking-wider">
                                                    Network: {{ $network }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <button type="button"
                                        x-on:click="navigator.clipboard.writeText(@js(number_format((float) ($payAmount ?? 0), 8, '.', ''))); copied='amount'; setTimeout(() => copied='', 1400);"
                                        class="mt-3 w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-sm bg-yellow-500/15 hover:bg-yellow-500/25 border border-yellow-500/40 text-yellow-200 transition">
                                        <span x-show="copied !== 'amount'">Copy amount</span>
                                        <span x-show="copied === 'amount'" x-cloak class="font-semibold">Copied!</span>
                                    </button>
                                </div>

                                <div class="rounded-2xl border border-gray-700 bg-gray-900/60 p-4">
                                    <p class="text-gray-400 text-xs uppercase tracking-wider">Wallet address</p>
                                    <p class="mt-2 text-white font-mono text-sm break-all leading-5 bg-gray-950/60 rounded-lg px-3 py-2 border border-gray-700/60 select-all">
                                        {{ $payAddress ?? 'Loading address…' }}
                                    </p>

                                    <button type="button"
                                        x-on:click="navigator.clipboard.writeText(@js((string) $payAddress)); copied='addr'; setTimeout(() => copied='', 1400);"
                                        class="mt-3 w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-sm bg-gray-700/60 hover:bg-gray-700 border border-gray-600 text-white transition">
                                        <span x-show="copied !== 'addr'">Copy address</span>
                                        <span x-show="copied === 'addr'" x-cloak class="font-semibold text-green-300">Copied!</span>
                                    </button>
                                </div>

                                @if (!empty($timeLimit) && in_array($statusHuman, ['pending','unpaid','waiting'], true))
                                    <div class="rounded-2xl border border-gray-700 bg-gray-900/60 p-3 text-center text-xs text-gray-400">
                                        ⏳ Pay within {{ round($timeLimit / 60) }} minutes after creation to avoid rate changes.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-700 bg-gray-900/60 p-4 space-y-3">
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <p class="text-sm text-gray-300">Status updates automatically every 7 seconds.</p>
                                <button type="button" wire:click="refreshPaymentStatus" wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 disabled:opacity-60 text-white text-sm font-semibold">
                                    <span wire:loading.remove wire:target="refreshPaymentStatus">Check now</span>
                                    <span wire:loading wire:target="refreshPaymentStatus">Checking…</span>
                                </button>
                            </div>
                            <ol class="list-decimal list-inside space-y-1 text-xs text-gray-400">
                                <li>Open the wallet you use for {{ $this->getCurrencyLabel() }}.</li>
                                <li>Scan the QR or paste the address.</li>
                                <li>Send the exact amount shown above (double-check the network).</li>
                            </ol>
                        </div>
                    @endif

                    @if ($isPartial)
                        <div class="flex items-start bg-orange-500/10 text-orange-300 rounded-2xl p-4 space-x-3 border border-orange-500/20">
                            <div class="text-sm">
                                <p class="font-semibold mb-1">We received less than the required amount.</p>
                                <p class="text-orange-200/80">Your order is on hold until the remaining balance is paid. Contact support with your order number.</p>
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-3 mt-2">
                            <a href="mailto:support@example.com?subject=Underpaid order {{ $entity->order_number }}"
                               class="px-6 py-3 bg-orange-600 text-white rounded-xl hover:bg-orange-500 transition text-center font-semibold">Contact Support</a>
                            <a href="{{ route('home') }}"
                               class="px-6 py-3 bg-gray-700 text-white rounded-xl hover:bg-gray-600 transition text-center font-semibold">Back to Home</a>
                        </div>
                    @endif

                    @if ($isPaid)
                        <div class="flex items-center bg-green-500/10 text-green-400 rounded-2xl p-4 space-x-3 border border-green-500/20">
                            <span class="text-sm font-medium">
                                Your payment has been confirmed. {{ $isPreOrder ? 'Your pre-order is being processed by the team (24–72h).' : 'You can access your order details now.' }}
                            </span>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-3 mt-2">
                            @if ($order)
                                <a href="{{ route('user.orders.details', ['order' => $order->order_number]) }}"
                                   class="px-6 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition text-center font-semibold">View Order Details</a>
                            @endif
                            <a href="{{ route('home') }}"
                               class="px-6 py-3 bg-gray-700 text-white rounded-xl hover:bg-gray-600 transition text-center font-semibold">Back to Home</a>
                        </div>
                    @endif

                    @if ($isFailed)
                        <div class="flex items-center bg-red-500/10 text-red-300 rounded-2xl p-4 space-x-3 border border-red-500/20">
                            <span class="text-sm font-medium">This payment was not completed. Please try again, or create a new checkout.</span>
                        </div>
                        <a href="{{ route('cart') }}"
                           class="block w-full text-center px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-500 transition font-semibold">Go back to cart</a>
                    @endif

                    @if ($isExpired)
                        <div class="flex items-center bg-gray-500/10 text-gray-300 rounded-2xl p-4 space-x-3 border border-gray-500/20">
                            <span class="text-sm font-medium">The payment window expired before any funds were received.</span>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-3 mt-2">
                            <a href="{{ route('cart') }}"
                               class="px-6 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-500 transition text-center font-semibold">Try again</a>
                            <a href="{{ route('home') }}"
                               class="px-6 py-3 bg-gray-700 text-white rounded-xl hover:bg-gray-600 transition text-center font-semibold">Back to Home</a>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-900 px-8 py-4 text-gray-400 text-xs text-center border-t border-gray-700/70 rounded-b-3xl">
                    Powered by NOWPayments · Payment ID: {{ $paymentId ?? '—' }}
                </div>
            </div>
        </div>
    @else
        <div class="mt-10 max-w-xl mx-auto text-center bg-gray-800/80 rounded-2xl p-8 border border-gray-700">
            <h3 class="text-white text-xl font-bold">Order not found</h3>
            <p class="text-gray-400 mt-2 text-sm">We couldn't locate this order. Please check your orders history.</p>
            <a href="{{ route('user.orders') }}" class="inline-block mt-5 px-5 py-2.5 bg-red-600 hover:bg-red-500 rounded-xl text-white font-semibold">My orders</a>
        </div>
    @endif

    <style>
    @keyframes confetti-fall {
        0%   { transform: translateY(0) rotate(0deg);     opacity: 1; }
        100% { transform: translateY(120%) rotate(360deg); opacity: 0; }
    }
    .animate-confetti { animation: confetti-fall 2s ease-out forwards; }
    </style>
</div>
