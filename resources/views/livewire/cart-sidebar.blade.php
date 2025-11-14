<div>
    <div x-data="{ open: false }" class="relative">
        <!-- Floating Cart Button with Glowing Badge -->
        <button @click="open = true"
            class="fixed right-4 top-1/2 transform -translate-y-1/2 bg-gradient-to-r from-purple-700 to-pink-600 text-white p-5 rounded-full shadow-2xl hover:scale-110 hover:from-pink-600 hover:to-purple-700 transition-all duration-300 ease-out">
            <i class="fas fa-shopping-cart fa-lg"></i>
            <span
                class="absolute -top-2 -right-2 bg-yellow-300 text-purple-800 font-bold text-xs w-6 h-6 flex items-center justify-center rounded-full shadow-md border border-yellow-500">
                {{ $totalCarts }}
            </span>
        </button>

        <!-- Cart Sidebar -->
        <div x-show="open" @click.away="open = false" x-cloak
            class="fixed z-50 top-0 right-0 w-96 h-full bg-gradient-to-b from-gray-900/90 via-purple-900/80 to-black/90 backdrop-blur-xl text-white shadow-2xl transform transition-transform duration-500 ease-in-out"
            x-transition:enter="translate-x-full" x-transition:enter-start="translate-x-0"
            x-transition:leave="translate-x-full" x-transition:leave-start="translate-x-0">

            <div class="p-6 flex flex-col h-full relative">

                <!-- Close Button -->
                <button @click="open = false"
                    class="absolute top-4 right-4 text-gray-300 hover:text-yellow-400 transition-all duration-300">
                    <i class="fas fa-times fa-lg"></i>
                </button>

                <!-- Header -->
                <div class="mb-6 text-center">
                    <h2 class="text-4xl font-extrabold bg-gradient-to-r from-yellow-300 to-pink-400 bg-clip-text text-transparent">
                        Your Cart
                    </h2>
                    <p class="text-sm text-gray-400 mt-1">Total Items: {{ $totalItems }}</p>
                </div>

                <hr class="border-gray-700 opacity-60" />

                <!-- Cart Items -->
                <div class="flex-grow overflow-y-auto mt-4 space-y-4 pr-1 custom-scroll">
                    @forelse($cartItems as $item)
                        <div
                            class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-gray-800/60 to-gray-700/40 shadow-lg backdrop-blur-sm hover:scale-[1.02] hover:from-purple-800/40 hover:to-gray-700/50 transition-all duration-300 ease-out">
                            <div>
                                <p class="font-semibold text-lg text-white drop-shadow-sm">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-300">
                                    x{{ $item['quantity'] }} • ${{ number_format($item['price'], 2) }}
                                </p>
                            </div>
                            <p class="font-semibold text-lg text-yellow-300">
                                ${{ number_format($item['price'] * $item['quantity'], 2) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center mt-10 italic">Your divine basket is empty ✨</p>
                    @endforelse
                </div>

                <!-- Footer -->
                <div class="border-t border-gray-700 pt-4 mt-6">
                    <p class="text-2xl font-bold mb-3 bg-gradient-to-r from-yellow-400 to-pink-300 bg-clip-text text-transparent">
                        Total: ${{ number_format($totalPrice, 2) }}
                    </p>
                    <div class="flex space-x-4">
                        <a href="/cart"
                            class="flex-grow bg-gradient-to-r from-purple-600 to-purple-800 text-white text-center px-4 py-3 rounded-xl shadow-md hover:shadow-yellow-400/40 hover:from-yellow-400 hover:to-pink-400 transition-all duration-300">
                            View Cart
                        </a>
                        <a href="/checkout"
                            class="flex-grow bg-gradient-to-r from-green-500 to-emerald-700 text-white text-center px-4 py-3 rounded-xl shadow-md hover:shadow-green-400/40 hover:from-emerald-500 hover:to-lime-500 transition-all duration-300">
                            Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional Custom Scrollbar -->
    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #a855f7, #ec4899);
            border-radius: 4px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>
</div>
