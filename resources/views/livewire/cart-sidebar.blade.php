<div x-data="{ open: false }" class="relative">
    <!-- Cart Button with Badge -->
    <button @click="open = true"
        class="fixed right-4 top-1/2 transform -translate-y-1/2 bg-red-600 text-white p-4 rounded-full shadow-lg hover:bg-red-700 transition">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <span
            class="absolute -top-2 -right-2 bg-white text-red-600 font-bold text-xs w-6 h-6 flex items-center justify-center rounded-full shadow">
            {{ $totalCarts }}
        </span>
    </button>

    <!-- Cart Sidebar -->
    <div x-show="open" @click.away="open = false" x-cloak
        class="fixed z-50 top-0 right-0 w-80 h-full bg-gray-900 text-white shadow-lg transform transition-transform duration-300 ease-in-out"
        x-transition:enter="translate-x-full" x-transition:enter-start="translate-x-0"
        x-transition:leave="translate-x-full" x-transition:leave-start="translate-x-0">
        <div class="p-6 flex flex-col h-full">
            <!-- Close Button -->
            <button @click="open = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
                <i class="fas fa-times fa-lg"></i>
            </button>

            <!-- Header -->
            <h2 class="text-3xl font-bold mb-4">Total ({{ $totalItems }})</h2>
            <hr class="border-gray-700" />

            <!-- Cart Items -->
            <div class="flex-grow overflow-y-auto mt-4 space-y-4">
                @forelse($cartItems as $item)
                    <div
                        class="flex items-center justify-between p-3 bg-gray-800 rounded-lg shadow-lg hover:bg-gray-700 transition">
                        <div>
                            <p class="font-semibold text-lg text-white">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-400">x{{ $item['quantity'] }} -
                                ${{ number_format($item['price'], 2) }}</p>
                        </div>
                        <p class="font-semibold text-lg text-white">
                            ${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center mt-10">Your cart is empty.</p>
                @endforelse
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-700 pt-4 mt-4">
                <p class="text-xl font-semibold mb-2 text-white">Total: ${{ number_format($totalPrice, 2) }}</p>
                <div class="flex space-x-4">
                    <a href="/cart"
                        class="flex-grow bg-red-600 text-white text-center px-4 py-2 rounded-lg shadow hover:bg-red-700 transition">
                        View Cart
                    </a>
                    <a href="/checkout"
                        class="flex-grow bg-green-600 text-white text-center px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
                        Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
