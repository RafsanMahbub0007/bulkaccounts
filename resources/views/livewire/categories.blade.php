<section class="bg-gray-900 text-white py-16 relative overflow-hidden">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16" id="product-details">
            <!-- Product details section (left column) -->
            <div class="space-y-6 lg:col-span-2">
                <div class="bg-gray-800 p-8 rounded-xl shadow-xl space-y-6" x-data="{ quantity: @entangle('quantity') }">
                    <div class="flex items-center space-x-4">
                        <div class="bg-red-600 text-white p-4 rounded-full">
                            <i class="fas fa-envelope-open-text fa-2x"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-extrabold text-red-500">{{ $product->name }}</h1>
                            <p class="text-lg text-gray-300">Category: {{ $product->category->name }}</p>
                        </div>
                    </div>
                    <p class="text-md text-gray-400">Price per unit:
                        <span class="font-semibold text-red-500">${{ number_format($product->price, 2) }}</span>
                    </p>
                    <div class="mt-4 flex items-center gap-4">
                        <label for="quantity" class="text-lg text-gray-300">Quantity:</label>
                        <div class="flex items-center">
                            <button @click="if (quantity > 1) quantity--"
                                class="bg-gray-700 text-white px-4 py-2 rounded-l-lg hover:bg-gray-600 transition">-</button>
                            <input type="number" id="quantity" name="quantity" x-model="quantity"
                                wire:model="quantity" class="bg-gray-700 text-white p-2 w-20 text-center" min="1"
                                :max="{{ $product->stock }}" />
                            <button @click="if (quantity < {{ $product->stock }}) quantity++"
                                class="bg-gray-700 text-white px-4 py-2 rounded-r-lg hover:bg-gray-600 transition">+</button>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-lg text-gray-300">
                            Total:
                            <span x-text="'$' + (quantity * {{ $product->price }}).toFixed(2)"
                                class="font-semibold text-red-500"></span>
                        </div>
                        <div class="flex gap-4 w-full">
                            <!-- CART / PREORDER -->
                        <div class="flex flex-col sm:flex-row gap-3 mt-4 pointer-events-auto">
                            
                            <!-- Add to Cart / Pre-Order -->
                            <div class="flex flex-col flex-1">
                                <span wire:click="addToCart({{ $product->id }})"
                                    class="w-full
                                        flex items-center justify-center
                                        px-4 sm:px-5 py-2 sm:py-2.5
                                        text-[13px] sm:text-sm font-semibold
                                        whitespace-nowrap
                                        rounded-full
                                        bg-gradient-to-r {{ $product->stock <= 0 ? 'from-cyan-500 to-blue-500' : 'from-pink-500 to-purple-500' }} text-white
                                        shadow-lg {{ $product->stock <= 0 ? 'shadow-cyan-500/30' : 'shadow-pink-500/30' }}
                                        transition-all duration-300 cursor-pointer
                                        hover:scale-105 active:scale-95">
                                    {{ $product->stock <= 0 ? '' : 'Add to Cart' }}
                                    @if($product->stock <= 0)
                                        <span class="flex items-center gap-2">
                                            <i class="fas fa-rotate"></i> Pre-Order Now
                                        </span>
                                    @endif
                                </span>
                                @if($product->stock <= 0)
                                    <span class="text-[10px] sm:text-xs text-cyan-500 text-center mt-1 font-medium animate-pulse">
                                        Delivery: 24-72 hours
                                    </span>
                                @endif
                            </div>

                            <!-- Buy Now -->
                            @if($product->stock > 0)
                                <span wire:click="buyNow({{ $product->id }})"
                                    class="flex-1 min-w-0
                                           flex items-center justify-center
                                           px-4 sm:px-5 py-2 sm:py-2.5
                                           text-[13px] sm:text-sm font-semibold
                                           whitespace-nowrap
                                           rounded-full
                                           bg-gradient-to-r from-cyan-500 to-blue-500 text-white
                                           shadow-lg shadow-cyan-500/30
                                           transition-all duration-300 cursor-pointer
                                           hover:scale-105 active:scale-95">
                                    Buy&nbsp;Now
                                </span>
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
                <!-- Product Content -->
                <div class="bg-gray-800 p-8 rounded-xl shadow-xl space-y-6 mt-8">
                    <h2 class="text-2xl font-extrabold text-red-500">Product Content</h2>
                    <p class="text-lg text-gray-300">{{ $product->content }}</p>
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
                                <p class="text-xl text-red-500">${{ number_format($relatedProduct->price, 2) }}</p>
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
