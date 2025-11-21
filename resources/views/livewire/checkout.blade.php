<div class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-6 max-w-6xl">

        <h1 class="text-4xl font-bold text-red-500 mb-12 text-center tracking-wide">
            Checkout
        </h1>

        <!-- AUTH CHECK -->
        @guest
            <div class="bg-gray-800 p-10 rounded-xl text-center shadow-xl border border-gray-700">
                <h2 class="text-2xl font-bold mb-3">Please Login to Continue</h2>
                <p class="text-gray-400 mb-6">You must be logged in to complete your checkout.</p>

                <a href="{{ route('login') }}"
                   class="px-6 py-3 bg-red-600 rounded-lg text-white font-semibold hover:bg-red-700">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="px-6 py-3 bg-gray-700 rounded-lg ml-3 hover:bg-gray-600">
                    Create Account
                </a>
            </div>

        @else
            <!-- MAIN FORM -->
            <form wire:submit.prevent="proceedToPayment" class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                <!-- LEFT — BILLING DETAILS -->
                <div class="bg-gray-800/70 p-8 rounded-xl shadow-xl border border-gray-700/40">
                    <h2 class="text-2xl font-semibold mb-6 pb-3 border-b border-gray-700">
                        Billing Information
                    </h2>

                    <!-- Error -->
                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-red-600 rounded-lg shadow-lg text-white text-center">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="space-y-6">

                        <!-- Full Name -->
                        <div>
                            <label class="block text-gray-300 mb-2 font-medium">Full Name</label>
                            <input type="text" wire:model="name"
                                class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white focus:ring-2 focus:ring-red-500 outline-none"
                                placeholder="Enter your name"  required >
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-gray-300 mb-2 font-medium">Phone Number</label>
                            <input type="text" wire:model="number"
                                class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white focus:ring-2 focus:ring-red-500 outline-none"
                                placeholder="Enter your phone number" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-gray-300 mb-2 font-medium">Email Address</label>
                            <input type="email" wire:model="email"
                                class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white
                                focus:ring-2 focus:ring-red-500 outline-none"
                                placeholder="Enter your email" required>
                        </div>

                        <!-- TERMS CHECK -->
                        <div class="flex items-start gap-3 mt-4">
                            <input type="checkbox" wire:model="acceptedTerms" required
                                class="w-5 h-5 mt-1 rounded border-gray-600 bg-gray-700 text-red-500 focus:ring-red-500">

                            <label class="text-gray-300 leading-6">
                                I accept the
                                <a href="{{ route('terms') }}"
                                   class="text-red-400 underline hover:text-red-300">
                                    Terms & Conditions
                                </a>
                            </label>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full py-3 mt-4 bg-red-600 rounded-lg text-white font-semibold
                            hover:bg-red-700 transition shadow-lg">
                            Proceed to Pay
                        </button>

                    </div>
                </div>

                <!-- RIGHT — ORDER SUMMARY -->
                <div class="bg-gray-800/70 p-8 rounded-xl shadow-xl border border-gray-700/40 lg:sticky lg:top-20">

                    <h3 class="text-2xl font-semibold mb-6 pb-3 border-b border-gray-700">
                        Order Summary
                    </h3>

                    <div class="space-y-4 text-lg">
                        <div class="flex justify-between">
                            <p>Subtotal:</p>
                            <p class="font-semibold text-red-500">
                                ${{ number_format($total, 2) }}
                            </p>
                        </div>

                        <div class="flex justify-between font-bold text-xl">
                            <p>Total:</p>
                            <p class="text-red-500">
                                ${{ number_format($total, 2) }}
                            </p>
                        </div>
                    </div>

                    <!-- Product List -->
                    <div class="mt-10 border-t border-gray-700 pt-6">
                        <h4 class="text-xl font-semibold mb-4">Products</h4>

                        <div class="space-y-4">
                            @foreach ($cartItems as $item)
                                <div class="flex justify-between items-center bg-gray-900/60 p-4 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ image_path($item['image']) }}"
                                             alt="{{ $item['name'] }}"
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

                    <!-- Continue Shopping -->
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
