<div class="bg-gray-900 text-white py-16">
    <div class="container mx-auto px-6">
        <h1 class="text-3xl font-bold text-red-500 mb-8 text-center">Checkout</h1>

        <!-- Error Message -->
        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-600 rounded-lg shadow-lg text-white text-center">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Cart Items Section -->
            <div class="space-y-6">
                <h2 class="text-2xl font-semibold mb-6">Your Cart</h2>
                @foreach ($cartItems as $item)
                    <div class="bg-gray-800 p-6 rounded-lg shadow-lg flex justify-between items-center">
                        <!-- Product Details -->
                        <div class="flex items-center space-x-4">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                class="w-20 h-20 rounded-lg object-cover">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $item['name'] }}</h3>
                                <p class="text-gray-400">Quantity: {{ $item['quantity'] }}</p>
                                <p class="text-gray-400">Price: ${{ number_format($item['price'], 2) }}</p>
                            </div>
                        </div>
                        <!-- Total Price -->
                        <div class="text-lg font-semibold text-red-500">
                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Summary & Action Section (Fixed on Right) -->
            <div class="lg:sticky lg:top-0 bg-gray-800 p-6 rounded-lg shadow-lg">
                <div class="space-y-8">
                    <!-- Cart Summary -->
                    <div class="flex justify-between items-center border-b-2 border-gray-600 pb-4 mb-6">
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

                    <!-- Place Order Button -->
                    <div class="mt-8 flex justify-center">
                        <button wire:click.prevent="placeOrder"
                            class="bg-red-600 px-8 py-3 rounded-lg shadow-lg text-white hover:bg-red-700 w-full lg:w-auto">
                            Place Order
                        </button>
                    </div>

                    <!-- Additional Actions -->
                    <div class="mt-6 flex justify-center space-x-4">
                        <a href="{{ route('home') }}"
                            class="bg-gray-700 px-6 py-3 rounded-lg shadow-lg text-white hover:bg-gray-800">
                            Continue Shopping
                        </a>
                        <a href="{{ route('user.orders') }}"
                            class="bg-gray-700 px-6 py-3 rounded-lg shadow-lg text-white hover:bg-gray-800">
                            View Your Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
