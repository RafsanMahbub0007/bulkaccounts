<section class="bg-gray-900 min-h-screen py-8">
    <div class="container mx-auto px-4 lg:px-8">
        <h1 class="text-4xl font-extrabold text-white mb-6">Your Cart</h1>

        <!-- Cart Items -->
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl">
            @if (count($cartItems) > 0)
                <ul class="space-y-6">
                    @foreach ($cartItems as $item)
                        <li class="flex items-center justify-between text-white">
                            <div class="flex items-center">
                                <img src="{{ asset('/storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                    class="w-16 h-16 object-cover rounded-lg mr-4">
                                <div>
                                    <p class="font-medium">{{ $item['name'] }}</p>
                                    <p class="text-gray-400">{{ number_format($item['price'], 2) }} BDT</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4 ml-auto">
                                <!-- Quantity Input -->
                                <input type="number" min="1" max="10000" value="{{ $item['quantity'] }}"
                                    class="w-40 p-2 text-center text-black rounded-md"
                                    wire:change.debounce.300ms="updateQuantity({{ $item['id'] }}, $event.target.value)">

                                <!-- Remove Button -->
                                <button class="bg-red-600 hover:bg-red-700 text-white py-1 px-4 rounded-md"
                                    wire:click="removeFromCart({{ $item['id'] }})">
                                    Remove
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-white text-center">Your cart is empty.</p>
            @endif
        </div>

        <!-- Total Price -->
        <div class="mt-6 text-white text-lg font-medium flex justify-between">
            <p>Total Price:</p>
            <p class="font-semibold">{{ number_format($totalPrice, 2) }} BDT</p>
        </div>

        <!-- Checkout Button -->
        @if (count($cartItems) > 0)
            <div class="mt-6 text-right">
                <a href="{{ route('checkout') }}" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md">
                    Proceed to Checkout
                </a>
            </div>
        @endif
    </div>

    <!-- Toast Notifications -->
    <x-toast on="cartUpdated" type="success">
        Success
    </x-toast>

    <x-toast on="cartUpdateFailed" type="failed">
        Out of Stock
    </x-toast>
</section>
