<section class="bg-gray-950 min-h-screen py-14">
    <div class="container mx-auto px-4 lg:px-10">

        <!-- Title -->
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-10 tracking-wide">
            Your Cart
        </h1>

        @if (count($cartItems) > 0)
            <!-- Cart Container -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                <!-- LEFT — CART ITEMS -->
                <div class="lg:col-span-2 space-y-6">
                    @foreach ($cartItems as $item)
                        <div class="bg-gray-900/70 border border-gray-800 p-6 rounded-xl shadow-xl
                                    flex items-center justify-between hover:border-gray-700 transition">

                            <!-- Product Info -->
                            <div class="flex items-center space-x-5">
                                <img src="{{ image_path($item['image']) }}"
                                     class="w-20 h-20 object-cover rounded-xl shadow-md">

                                <div>
                                    <h3 class="text-lg font-semibold text-white">{{ $item['name'] }}</h3>
                                    <p class="text-gray-400 text-sm">
                                        {{ number_format($item['price'], 2) }} BDT
                                    </p>
                                </div>
                            </div>

                            <!-- Controls -->
                            <div class="flex items-center space-x-4">

                                <!-- Quantity Input -->
                                <input type="number" min="1" max="10000"
                                       value="{{ $item['quantity'] }}"
                                       class="w-20 p-2 text-center bg-gray-800 border border-gray-700
                                       rounded-lg text-white focus:ring-2 focus:ring-red-500"
                                       wire:change.debounce.300ms="updateQuantity({{ $item['id'] }}, $event.target.value)">

                                <!-- Remove Button -->
                                <button wire:click="removeFromCart({{ $item['id'] }})"
                                        class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800
                                               text-white px-4 py-2 rounded-lg font-semibold shadow-md transition">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- RIGHT — ORDER SUMMARY -->
                <div class="bg-gray-900/70 border border-gray-800 p-6 rounded-xl shadow-xl h-fit sticky top-10">

                    <h2 class="text-2xl font-bold text-white mb-6 tracking-wide border-b border-gray-700 pb-3">
                        Order Summary
                    </h2>

                    <!-- Total -->
                    <div class="flex justify-between text-lg mb-6">
                        <span class="text-gray-300">Total:</span>
                        <span class="text-red-400 font-bold text-xl">
                            {{ number_format($totalPrice, 2) }} BDT
                        </span>
                    </div>

                    <!-- Checkout Button -->
                    <a href="{{ route('checkout') }}"
                       class="block w-full text-center bg-gradient-to-r from-red-600 to-red-700
                              hover:from-red-700 hover:to-red-800 text-white py-3 rounded-lg
                              font-semibold shadow-lg transition">
                        Proceed to Checkout
                    </a>

                    <!-- Continue Shopping -->
                    <a href="{{ route('home') }}"
                       class="block w-full text-center mt-4 py-3 rounded-lg bg-gray-800
                              hover:bg-gray-700 text-gray-300 transition">
                        Continue Shopping
                    </a>
                </div>

            </div>
        @else
            <!-- EMPTY CART -->
            <div class="bg-gray-900/60 border border-gray-800 p-10 rounded-xl text-center shadow-xl">
                <h2 class="text-2xl text-white font-bold mb-3">Your cart is empty</h2>
                <p class="text-gray-400">Looks like you haven't added anything yet.</p>
                <a href="{{ route('home') }}"
                   class="inline-block mt-6 bg-red-600 hover:bg-red-700 text-white py-2 px-5 rounded-lg">
                    Start Shopping
                </a>
            </div>
        @endif

        <!-- Toast Notifications -->
        <x-toast on="cartUpdated" type="success">
            Success
        </x-toast>

        <x-toast on="cartUpdateFailed" type="failed">
            Out of Stock
        </x-toast>

    </div>
</section>
