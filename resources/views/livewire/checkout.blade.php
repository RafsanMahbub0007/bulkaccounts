<div class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl font-bold text-red-500 mb-8 text-center">Checkout</h1>

        <!-- Error Message -->
        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-600 rounded-lg shadow-lg text-white text-center">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- ✅ Only ONE form -->
        <form wire:submit.prevent="proceedToPayment">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Checkout Form Section -->
                <div class="bg-gray-800 p-8 rounded-lg shadow-lg space-y-6">
                    <h2 class="text-2xl font-semibold mb-6 border-b border-gray-700 pb-3">Billing Details</h2>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-gray-300 mb-2">Full Name</label>
                            <input type="text" wire:model="name"
                                class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white focus:ring-2 focus:ring-red-500 outline-none"
                                placeholder="Enter your name" required>
                        </div>

                        <div>
                            <label class="block text-gray-300 mb-2">Phone Number</label>
                            <input type="text" wire:model="number"
                                class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white focus:ring-2 focus:ring-red-500 outline-none"
                                placeholder="Enter your number" required>
                        </div>

                        <div>
                            <label class="block text-gray-300 mb-2">Email Address</label>
                            <input type="email" wire:model="email"
                                class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white focus:ring-2 focus:ring-red-500 outline-none"
                                placeholder="Enter your email" required>
                        </div>

                        <button type="submit"
                            class="bg-red-600 w-full py-3 rounded-lg text-white font-semibold hover:bg-red-700 transition duration-200">
                            Proceed to Pay
                        </button>
                    </div>
                </div>

                <!-- Order Summary + Product Details -->
                <div class="lg:sticky lg:top-0 bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="space-y-8">
                        <!-- Order Summary -->
                        <div class="border-b-2 border-gray-600 pb-4 mb-6">
                            <h3 class="text-2xl font-semibold">Order Summary</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <p class="text-lg">Subtotal:</p>
                                <p class="text-lg font-semibold text-red-500">${{ number_format($total, 2) }}</p>
                            </div>
                            <div class="flex justify-between font-semibold text-xl">
                                <p>Total:</p>
                                <p class="text-xl text-red-500">${{ number_format($total, 2) }}</p>
                            </div>
                        </div>

                        <!-- Product Details Below Summary -->
                        <div class="border-t border-gray-700 pt-6">
                            <h4 class="text-xl font-semibold mb-4">Products</h4>
                            <div class="space-y-4">
                                @foreach ($cartItems as $item)
                                    <div class="flex justify-between items-center bg-gray-900 p-4 rounded-lg">
                                        <div class="flex items-center space-x-4">
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
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
                        <div class="mt-6 text-center">
                            <a href="{{ route('home') }}"
                                class="inline-block bg-gray-700 px-6 py-3 rounded-lg shadow-lg text-white hover:bg-gray-800">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
