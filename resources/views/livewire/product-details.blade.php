<section class="bg-gray-900 text-white py-16 relative overflow-hidden">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16" id="product-details">
            <div class="space-y-6 lg:col-span-2">
                <div class="bg-gray-800 p-8 rounded-xl shadow-xl space-y-6" x-data="{ quantity: @entangle('quantity') }">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <img src="{{ asset('/storage/' . $product->subcategory->image) }}" alt="{{ $product->name }}"
                                class="w-16 h-16  rounded-full shadow-md">
                        </div>
                        <div>
                            <h1 class="text-3xl font-extrabold text-red-500">{{ $product->name }}</h1>
                            <p class="text-lg text-gray-300">Category: {{ $product->category->name }}</p>
                            <p class="text-lg text-gray-300">Sub-category: {{ $product->subCategory->name }}</p>
                        </div>
                    </div>
                    <p class="text-md text-gray-400">Price per unit:
                        <span class="font-semibold text-red-500">${{ number_format($product->selling_price, 2) }}</span>
                    </p>
                    <div class="mt-4 flex items-center gap-4">
                        <label for="quantity" class="text-lg text-gray-300">Quantity:</label>
                        <div x-data="{ quantity: @entangle('quantity') }" class="flex items-center">
                            <!-- Decrement button -->
                            <button @click="quantity = Math.max(quantity - 1, {{ $product->min_order_qty }})"
                                class="bg-gray-700 text-white px-4 py-2 rounded-l-lg hover:bg-gray-600 transition">-</button>

                            <!-- Input -->
                            <input type="number" x-model.number="quantity" wire:model="quantity"
                                class="bg-gray-700 text-white p-2 w-20 text-center"
                                @blur="if (quantity < {{ $product->min_order_qty }}) quantity = {{ $product->min_order_qty }};
                                if (quantity > {{ $product->stock }}) quantity = {{ $product->stock }};"
                                :min="{{ $product->min_order_qty }}" :max="{{ $product->stock }}" />

                            <!-- Increment button -->
                            <button @click="quantity = Math.min(quantity + 1, {{ $product->stock }})"
                                class="bg-gray-700 text-white px-4 py-2 rounded-r-lg hover:bg-gray-600 transition">+</button>
                        </div>


                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-lg text-gray-300">
                            Total:
                            <span x-text="'$' + (quantity * {{ $product->selling_price }}).toFixed(2)"
                                class="font-semibold text-red-500"></span>
                        </div>
                        <div class="flex gap-4">
                            <form wire:submit.prevent="addToCart">
                                <input type="hidden" name="quantity" :value="quantity" />
                                <button type="submit"
                                    class="bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-red-700 transition">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                            <form wire:submit.prevent="buyNow">
                                <input type="hidden" name="quantity" :value="quantity" />
                                <button type="submit"
                                    class="bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-green-700 transition">
                                    <i class="fas fa-credit-card"></i> Buy Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Product Content -->
                <div class="bg-gray-800 p-8 rounded-xl shadow-xl space-y-6 mt-8">
                    <h2 class="text-2xl font-extrabold text-red-500">Product Content</h2>
                    <p class="text-lg text-gray-300">{!! $product->content !!}</p>
                </div>
            </div>

            <!-- Related Products section (right column) -->
            <div class="lg:col-span-1">
                <div class="bg-gray-800 p-8 rounded-xl shadow-xl space-y-6">
                    <h2 class="text-2xl font-extrabold text-red-500">Related Products</h2>
                    <div class="space-y-4">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="bg-gray-700 p-4 rounded-lg shadow-md">
                                <h3 class="text-lg text-gray-300">{{ $relatedProduct->name }}</h3>
                                <p class="text-sm text-gray-400">{{ Str::limit($relatedProduct->description, 50) }}</p>
                                <p class="text-xl text-red-500">${{ number_format($relatedProduct->selling_price, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <x-toast on="cartUpdated" type="success">
        Success
    </x-toast>

    <x-toast on="cartUpdateFailed" type="failed">
        Out of Stock
    </x-toast>

</section>
