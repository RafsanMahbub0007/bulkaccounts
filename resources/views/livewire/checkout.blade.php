<div> {{-- SINGLE ROOT WRAPPER REQUIRED --}}

    {{-- Checkout Page --}}
    <div class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-6 max-w-6xl">

            <h1 class="text-4xl font-bold text-red-500 mb-12 text-center tracking-wide">
                Checkout
            </h1>

            @guest
                <div class="bg-gray-800 p-10 rounded-xl text-center shadow-xl border border-gray-700">
                    <h2 class="text-2xl font-bold mb-3">Please Login to Continue</h2>
                    <p class="text-gray-400 mb-6">You must be logged in to complete your checkout.</p>

                    <a href="{{ route('login') }}"
                        class="px-6 py-3 bg-red-600 rounded-lg text-white font-semibold hover:bg-red-700">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="px-6 py-3 bg-gray-700 rounded-lg ml-3 hover:bg-gray-600">
                        Create Account
                    </a>
                </div>
            @else
                <form wire:submit.prevent="proceedToPayment" class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                    <!-- Billing Details -->
                    <div class="bg-gray-800/70 p-8 rounded-xl shadow-xl border border-gray-700/40">
                        <h2 class="text-2xl font-semibold mb-6 pb-3 border-b border-gray-700">
                            Billing Information
                        </h2>

                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-600 rounded-lg text-white text-center">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-300 mb-2 font-medium">Full Name</label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white
                                   focus:ring-2 focus:ring-red-500 outline-none"
                                    required>
                            </div>

                            <div>
                                <label class="block text-gray-300 mb-2 font-medium">Phone Number</label>
                                <input type="text" wire:model="number"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white
                                   focus:ring-2 focus:ring-red-500 outline-none"
                                    required>
                            </div>

                            <div>
                                <label class="block text-gray-300 mb-2 font-medium">Email Address</label>
                                <input type="email" wire:model="email"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white
                                   focus:ring-2 focus:ring-red-500 outline-none"
                                    required>
                            </div>

                            <div class="flex items-start gap-3 mt-4">
                                <input type="checkbox" wire:model="acceptedTerms" required
                                    class="w-5 h-5 mt-1 rounded border-gray-600 bg-gray-700
                                   text-red-500 focus:ring-red-500">
                                <label class="text-gray-300 leading-6">
                                    I accept the
                                    <a href="{{ route('terms') }}" class="text-red-400 underline hover:text-red-300">
                                        Terms & Conditions
                                    </a>
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full py-3 mt-4 bg-red-600 rounded-lg text-white font-semibold
                                       hover:bg-red-700 transition shadow-lg">
                                Proceed to Pay
                            </button>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-gray-800/70 p-8 rounded-xl shadow-xl border border-gray-700/40 lg:sticky lg:top-20">
                        <h3 class="text-2xl font-semibold mb-6 pb-3 border-b border-gray-700">
                            Order Summary
                        </h3>

                        <div class="space-y-4 text-lg">
                            <div class="flex justify-between">
                                <p>Subtotal:</p>
                                <p class="font-semibold text-red-500">${{ number_format($total, 2) }}</p>
                            </div>

                            <div class="flex justify-between font-bold text-xl">
                                <p>Total:</p>
                                <p class="text-red-500">${{ number_format($total, 2) }}</p>
                            </div>
                        </div>

                        <div class="mt-10 border-t border-gray-700 pt-6">
                            <h4 class="text-xl font-semibold mb-4">Products</h4>

                            <div class="space-y-4">
                                @foreach ($cartItems as $item)
                                    <div class="flex justify-between items-center bg-gray-900/60 p-4 rounded-lg">
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ image_path($item['image']) }}" alt="{{ $item['name'] }}"
                                                class="w-16 h-16 rounded-lg object-cover">
                                            <div>
                                                <h3 class="font-semibold text-white">{{ $item['name'] }}</h3>
                                                <p class="text-gray-400 text-sm">Qty: {{ $item['quantity'] }}</p>
                                            </div>
                                        </div>
                                        <p class="font-semibold text-red-500">
                                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-8 text-center">
                            <a href="{{ route('home') }}"
                                class="inline-block bg-gray-700 px-6 py-3 rounded-lg shadow-lg text-white
                                  hover:bg-gray-800 transition">
                                Continue Shopping
                            </a>
                        </div>
                    </div>

                </form>
            @endguest

        </div>
    </div>


    {{-- ============================================= --}}
    {{--          PAYMENT MODAL (LIVEWIRE + ALPINE)     --}}
    {{-- ============================================= --}}

    <div x-data="{ open: @entangle('showPaymentModal') }" x-show="open" x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black/70 z-50">

        <div class="bg-gray-800 p-8 rounded-xl shadow-xl w-full max-w-lg border border-gray-700">

            <h2 class="text-2xl font-semibold text-center mb-6 text-red-400">
                Complete Your Payment
            </h2>

            <div class="text-center mb-6">
                <img src="/images/qrcode.png" class="mx-auto w-48 h-48 rounded-lg shadow-lg">
                <p class="text-gray-300 mt-3">Scan the QR Code to make payment With Binance</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-gray-300 mb-1 block">Transaction ID</label>
                    <input type="text" wire:model="transactionIdInput"
                        class="w-full bg-gray-700 px-4 py-2 rounded-lg focus:ring-2 focus:ring-red-500 text-white">
                </div>

                <div>
                    <label class="text-gray-300 mb-1 block">Amount</label>
                    <input type="text" value="{{ number_format($total, 2) }}" readonly
                        class="w-full bg-gray-700 px-4 py-2 rounded-lg text-gray-400 border border-gray-600">
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button @click="open = false" class="px-5 py-2 bg-gray-600 rounded-lg hover:bg-gray-700 text-white">
                    Cancel
                </button>

                <button wire:click="confirmPayment"
                    class="px-5 py-2 bg-red-600 rounded-lg hover:bg-red-700 text-white font-semibold">
                    Confirm Payment
                </button>
            </div>
        </div>
    </div>

</div>

{{-- SUCCESS EVENT --}}
<script>
Livewire.on('order-success', data => {
    alert(data.message);
    if (data.redirect) {
        window.location.href = data.redirect;
    }
});
</script>
