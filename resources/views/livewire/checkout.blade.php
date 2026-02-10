<div>
    @php
        $seo = \App\Models\SeoSetting::where('page_name', 'checkout')->first();
        $title = $seo->meta_title ?? 'Secure Checkout';
        $description = $seo->meta_description ?? 'Complete your purchase securely. Instant delivery for verified accounts.';
        $keywords = $seo->meta_keywords ?? '';
    @endphp
    @section('title', $title)
    @section('description', $description)
    @section('keywords', $keywords)

    {{-- =========================
        CHECKOUT PAGE
    ========================= --}}
    <div class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-6 max-w-6xl">

            <h1 class="text-4xl font-bold text-red-500 mb-12 text-center">
                Checkout
            </h1>

            @guest
                <div class="bg-gray-800 p-10 rounded-xl text-center">
                    <h2 class="text-2xl font-bold mb-3">Login Required</h2>
                    <p class="text-gray-400 mb-6">
                        Please login to continue checkout.
                    </p>

                    <a href="{{ route('login') }}" class="px-6 py-3 bg-red-600 rounded-lg text-white font-semibold">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="px-6 py-3 bg-gray-700 rounded-lg ml-3">
                        Create Account
                    </a>
                </div>
            @else
                <form class="grid grid-cols-1 lg:grid-cols-2 gap-12" wire:submit.prevent>

                    {{-- =========================
                        BILLING INFORMATION
                    ========================= --}}
                    <div class="bg-gray-800/70 p-8 rounded-xl border border-gray-700">

                        <h2 class="text-2xl font-semibold mb-6 border-b border-gray-700 pb-3">
                            Billing Information
                        </h2>

                        @if ($errors->any())
                            <div class="bg-red-600 p-4 rounded-lg mb-4 text-sm">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="space-y-5">
                            <div>
                                <label class="text-gray-300 block mb-1">Full Name</label>
                                <input type="text" wire:model.defer="name"
                                    class="w-full bg-gray-700 px-4 py-3 rounded-lg focus:ring-red-500 focus:ring-2">
                            </div>

                            <div>
                                <label class="text-gray-300 block mb-1">Phone</label>
                                <input type="text" wire:model.defer="number"
                                    class="w-full bg-gray-700 px-4 py-3 rounded-lg focus:ring-red-500 focus:ring-2">
                            </div>

                            <div>
                                <label class="text-gray-300 block mb-1">Email</label>
                                <input type="email" wire:model.defer="email"
                                    class="w-full bg-gray-700 px-4 py-3 rounded-lg focus:ring-red-500 focus:ring-2">
                            </div>

                            <div class="flex items-start gap-3">
                                <input type="checkbox" wire:model="acceptedTerms" class="mt-1 text-red-600 rounded">
                                <p class="text-gray-300 text-sm">
                                    I agree to the
                                    <a href="{{ route('terms') }}" class="text-red-400 underline">
                                        Terms & Conditions
                                    </a>
                                </p>
                            </div>

                            {{-- =========================
                                PAYMENT BUTTON
                            ========================= --}}
                            <div class="flex gap-3 pt-3">
                                @if ($isTestMode)
                                    <button type="button" wire:click="proceedToPayment(true)"
                                        class="flex-1 bg-gray-700 py-3 rounded-lg font-semibold">
                                        Manual Payment
                                    </button>
                                @else
                                    <button type="button" wire:click="proceedToPayment(false)"
                                        class="flex-1 bg-red-600 py-3 rounded-lg font-semibold">
                                        Pay Online
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- =========================
                        ORDER SUMMARY
                    ========================= --}}
                    <div class="bg-gray-800/70 p-8 rounded-xl border border-gray-700 lg:sticky lg:top-20">

                        <h3 class="text-2xl font-semibold mb-6 border-b border-gray-700 pb-3">
                            Order Summary
                        </h3>

                        <div class="space-y-4">
                            @if (!empty($cartItems) && is_array($cartItems))
                                @foreach ($cartItems as $item)
                                    <div class="flex justify-between items-center bg-gray-900 p-4 rounded-lg">
                                        <div>
                                            <p class="font-semibold">{{ $item['name'] }}</p>
                                            <p class="text-sm text-gray-400">Qty: {{ $item['quantity'] }}</p>
                                        </div>
                                        <p class="text-red-500 font-semibold">
                                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-400">Your cart is empty.</p>
                            @endif
                        </div>

                        <div class="border-t border-gray-700 mt-6 pt-6 flex justify-between text-xl font-bold">
                            <span>Total</span>
                            <span class="text-red-500">
                                ${{ number_format($total ?? 0, 2) }}
                            </span>
                        </div>
                    </div>

                </form>
            @endguest
        </div>
    </div>

    {{-- =========================
        PAYMENT MODAL (Manual Payment Only)
    ========================= --}}
    <div x-data="{ open: @entangle('showPaymentModal') }" x-show="open" x-transition x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
        <div class="bg-gray-800 rounded-xl w-full max-w-md p-6" @click.outside="open = false">

            <h3 class="text-xl font-semibold text-center text-red-400 mb-4">
                Confirm Payment
            </h3>

            {{-- Only show Transaction ID if manualPayment --}}
            @if ($isTestMode)
                <div class="mb-4">
                    <label class="text-gray-300 block mb-1">Transaction ID</label>
                    <input type="text" wire:model.defer="transactionIdInput"
                        class="w-full bg-gray-700 px-4 py-2 rounded-lg">
                </div>
            @endif

            <div class="mb-6">
                <label class="text-gray-400 block mb-1">Amount</label>
                <input type="text" readonly value="${{ number_format($total ?? 0, 2) }}"
                    class="w-full bg-gray-700 px-4 py-2 rounded-lg text-gray-400">
            </div>

            <div class="flex gap-3">
                <button @click="open=false" class="flex-1 bg-gray-600 py-2 rounded-lg">
                    Cancel
                </button>

                <button wire:click="confirmPayment" wire:loading.attr="disabled"
                    class="flex-1 bg-red-600 py-2 rounded-lg font-semibold">
                    <span wire:loading.remove>Confirm</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- =========================
        MINIMUM ORDER MODAL
    ========================= --}}
    <div x-data="{ open: @entangle('showMinOrderModal') }" x-show="open" x-transition x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
        <div class="bg-gray-800 rounded-xl w-full max-w-md p-6 text-center" @click.outside="open = false">
            
            <div class="mb-4 text-yellow-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-white mb-2">
                Minimum Order Required
            </h3>

            <p class="text-gray-300 mb-6">
                The minimum order amount is <span class="font-bold text-red-500">$10.00</span>.<br>
                Your current total is <span class="font-bold text-white">${{ number_format($total ?? 0, 2) }}</span>.
            </p>

            <button @click="open = false" 
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                Okay, I'll add more
            </button>
        </div>
    </div>

    {{-- =========================
        SUCCESS EVENT
    ========================= --}}
    <script>
        Livewire.on('alert', data => {
            alert(data.message);
        });

        Livewire.on('order-success', e => {
            alert(e.message);
            if (e.redirect) {
                window.location.href = e.redirect;
            }
        });

        Livewire.on('redirect-to-payment', data => {
            window.location.href = data.url; // Open the invoice URL
        });
    </script>
</div>
