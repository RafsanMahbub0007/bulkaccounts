<div wire:poll.5s x-data="{ showConfetti: false }" x-init="
    if (@js($order->payment_status) === 'paid') {
        showConfetti = true;
        setTimeout(() => showConfetti = false, 3000);
    }
">
    @if ($order)
        <div class="relative mt-5 mb-5 max-w-md mx-auto bg-gray-800 rounded-3xl shadow-2xl border border-gray-700 transform transition duration-500 hover:-translate-y-2 hover:shadow-2xl animate-float overflow-hidden">

            {{-- Confetti Layer --}}
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

            {{-- Card Header --}}
            <div class="bg-gray-900 text-center px-8 py-6 rounded-t-3xl">
                <h2 class="text-white text-xl font-bold tracking-wide">Order #{{ $order->order_number }}</h2>
                <p class="text-gray-400 text-sm mt-1">Track the status of your order in real-time.</p>
            </div>

            {{-- Card Body --}}
            <div class="p-8 space-y-6">

                {{-- Payment Status --}}
                @php
                    $statusClasses = [
                        'paid'    => 'bg-green-500/20 text-green-400',
                        'failed'  => 'bg-red-500/20 text-red-400',
                        'pending' => 'bg-yellow-500/20 text-yellow-400',
                        'unpaid'  => 'bg-yellow-500/20 text-yellow-400',
                    ];

                    $statusLabels = [
                        'paid'    => 'Payment Confirmed',
                        'failed'  => 'Payment Failed',
                        'pending' => 'Waiting for Confirmation',
                        'unpaid'  => 'Waiting for Confirmation',
                    ];

                    $paymentStatus = $order->payment_status ?? 'pending';
                    $statusClass = $statusClasses[$paymentStatus] ?? $statusClasses['pending'];
                    $statusLabel = $statusLabels[$paymentStatus] ?? $statusLabels['pending'];
                @endphp

                <div class="flex items-center justify-between bg-gray-700 rounded-2xl p-4 shadow-inner">
                    <span class="text-gray-300 font-medium">Payment Status</span>

                    @if (in_array($paymentStatus, ['pending', 'unpaid']))
                        <span class="flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                            <span class="w-2 h-2 mr-2 bg-yellow-400 rounded-full animate-pulse"></span>
                            {{ $statusLabel }}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    @endif
                </div>

                {{-- Payment Confirmation Message --}}
                @if ($paymentStatus === 'paid')
                    <div class="flex items-center bg-green-500/10 text-green-400 rounded-2xl p-4 space-x-3 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium">Your payment has been confirmed. You can now access your order.</span>
                    </div>
                @endif

            </div>

            {{-- Card Footer --}}
            <div class="bg-gray-900 px-8 py-4 text-gray-400 text-xs text-center rounded-b-3xl">
                 Thanks for your patience
            </div>

        </div>
    @endif
    {{-- Floating & Confetti Animations --}}
<style>
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
.animate-float {
    animation: float 3s ease-in-out infinite;
}

@keyframes confetti-fall {
    0% { transform: translateY(0) rotate(0deg); opacity: 1; }
    100% { transform: translateY(120%) rotate(360deg); opacity: 0; }
}
.animate-confetti {
    animation: confetti-fall 2s ease-out forwards;
}
</style>
</div>


