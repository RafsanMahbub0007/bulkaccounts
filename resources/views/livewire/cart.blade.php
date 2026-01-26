<section class="bg-gray-950 min-h-screen py-14">
    <div class="container mx-auto px-4 lg:px-10">

        <!-- Title -->
        <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-10 tracking-wide">
            Your Cart
        </h1>

        <!-- Check if user is logged in -->
        @guest
            <!-- Not Logged In -->
            <div class="bg-gray-900/60 border border-gray-800 p-10 rounded-xl text-center shadow-xl">
                <h2 class="text-2xl text-white font-bold mb-3">Please Login</h2>
                <p class="text-gray-400 mb-4">You must be logged in to view your cart.</p>

                <a href="{{ route('login') }}"
                   class="inline-block bg-red-600 hover:bg-red-700 text-white py-2 px-5 rounded-lg">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="inline-block ml-3 bg-gray-700 hover:bg-gray-600 text-white py-2 px-5 rounded-lg">
                    Create Account
                </a>
            </div>

        @else
            <!-- LOGGED-IN USER INFO -->
            <div class="bg-gray-900/70 border border-gray-800 p-5 mb-10 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold text-white ">Welcome, {{ auth()->user()->name }}</h2>
            </div>

            <!-- CART SECTION -->
            @if (count($cartItems) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                    <!-- LEFT — CART ITEMS -->
                    <div class="lg:col-span-2 space-y-6">
                        @foreach ($cartItems as $item)
                            <div class="bg-gray-900/70 border border-gray-800 p-4 sm:p-6 rounded-xl shadow-xl
                                        flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-gray-700 transition">

                                <!-- Product Info -->
                                <div class="flex items-center gap-4">
                                    <img src="{{ image_path($item['image']) }}" loading="lazy" decoding="async"
                                         class="w-20 h-20 object-cover rounded-xl shadow-md">

                                    <div>
                                        <h3 class="text-lg font-semibold text-white">{{ $item['name'] }}</h3>
                                        <p class="text-gray-400 text-sm">
                                            {{ number_format($item['price'], 2) }} $
                                        </p>
                                    </div>
                                </div>

                                <!-- Controls -->
                                <div class="flex items-center gap-4">

                                    <input type="number" min="1" max="10000"
                                           value="{{ $item['quantity'] }}"
                                           class="w-20 p-2 text-center bg-gray-800 border border-gray-700
                                           rounded-lg text-white focus:ring-2 focus:ring-red-500"
                                           wire:change.debounce.300ms="updateQuantity({{ $item['id'] }}, $event.target.value)">

                                    <button wire:click="removeFromCart({{ $item['id'] }})"
                                            class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800
                                                   text-white px-4 py-2 rounded-lg font-semibold shadow-md transition">
                                        Remove
                                    </button>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- RIGHT — SUMMARY -->
                    <div class="bg-gray-900/70 border border-gray-800 p-6 rounded-xl shadow-xl h-fit sticky top-10">

                        <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700 pb-3">
                            Order Summary
                        </h2>

                        <div class="flex justify-between text-lg mb-6">
                            <span class="text-gray-300">Total:</span>
                            <span class="text-red-400 font-bold text-xl">
                                {{ number_format($totalPrice, 2) }} $
                            </span>
                        </div>

                        <a href="{{ route('checkout') }}"
                           class="block w-full text-center bg-gradient-to-r from-red-600 to-red-700
                                  hover:from-red-700 hover:to-red-800 text-white py-3 rounded-lg
                                  font-semibold shadow-lg transition">
                            Proceed to Checkout
                        </a>

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
        @endguest

        <!-- Toast Notifications -->
        <x-toast on="cartUpdated" type="success">Success</x-toast>
        <x-toast on="cartUpdateFailed" type="failed">Out of Stock</x-toast>

    </div>
</section>
