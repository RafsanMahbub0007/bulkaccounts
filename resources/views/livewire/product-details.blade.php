<section class="relative bg-gray-900 text-white py-20 overflow-hidden">

    <!-- Background Neon Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-32 -left-20 w-[600px] h-[600px] bg-pink-600/20 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-0 right-0 w-[700px] h-[700px] bg-purple-600/30 rounded-full blur-[180px]"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-14" id="product-details">

            <!-- LEFT CONTENT -->
            <div class="lg:col-span-2 space-y-10">

                <!-- PRODUCT CARD -->
                <div class="backdrop-blur-2xl bg-white/10 border border-white/10 p-10 rounded-3xl shadow-2xl space-y-8"
                     x-data="{ quantity: @entangle('quantity') }">

                    <!-- Title + Image -->
                    <div class="flex items-center gap-6">
                        <img src="{{ image_path($product->subcategory->image) }}"
                             class="w-20 h-20 rounded-2xl shadow-[0_0_20px_rgba(255,0,100,0.4)] object-cover" />

                        <div>
                            <h1 class="text-4xl font-extrabold text-pink-400 tracking-wide drop-shadow-lg">
                                {{ $product->name }}
                            </h1>

                            <p class="text-gray-300 text-lg mt-1">Category:
                                <span class="text-white font-semibold">
                                    {{ $product->category->name }}
                                </span>
                            </p>

                            <p class="text-gray-300 text-lg">Sub Category:
                                <span class="text-white font-semibold">
                                    {{ $product->subCategory->name }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- PRICE -->
                    <div class="mt-6">
                        <p class="text-xl text-gray-300">
                            Price per unit:
                            <span class="font-bold text-pink-400 text-3xl drop-shadow">
                                ${{ number_format($product->selling_price, 2) }}
                            </span>
                        </p>
                    </div>

                    <!-- QUANTITY -->
                    <div class="mt-6">
                        <label class="text-lg text-gray-300 block mb-3">Quantity</label>

                        <div class="flex items-center w-fit bg-white/5 border border-white/10 rounded-xl p-2 shadow-lg"
                             x-data="{ quantity: @entangle('quantity') }">

                            <!-- Minus -->
                            <button @click="quantity = Math.max(quantity - 1, {{ $product->min_order_qty }})"
                                    class="px-4 py-2 bg-gray-800/60 hover:bg-gray-700 text-white rounded-l-lg transition">
                                -
                            </button>

                            <!-- Input -->
                            <input type="number"
                                   x-model.number="quantity"
                                   wire:model="quantity"
                                   class="bg-transparent px-4 py-2 text-center w-20 text-xl font-semibold"
                                   @blur="
                                        if (quantity < {{ $product->min_order_qty }}) quantity = {{ $product->min_order_qty }};
                                        if (quantity > {{ $product->stock }}) quantity = {{ $product->stock }};
                                   " />

                            <!-- Plus -->
                            <button @click="quantity = Math.min(quantity + 1, {{ $product->stock }})"
                                    class="px-4 py-2 bg-gray-800/60 hover:bg-gray-700 text-white rounded-r-lg transition">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- TOTAL PRICE -->
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-xl text-gray-300">
                            Total:
                            <span x-text="'$' + (quantity * {{ $product->selling_price }}).toFixed(2)"
                                  class="font-extrabold text-pink-400 text-3xl drop-shadow-lg"></span>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="flex gap-4">

                            <!-- Add To Cart -->
                            <form wire:submit.prevent="addToCart">
                                <button type="submit"
                                    class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700
                                           text-white px-6 py-3 rounded-xl font-semibold shadow-lg transition flex items-center gap-2">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </form>

                            <!-- Buy Now -->
                            <form wire:submit.prevent="buyNow">
                                <button type="submit"
                                    class="bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700
                                           text-white px-6 py-3 rounded-xl font-semibold shadow-lg transition flex items-center gap-2">
                                    <i class="fas fa-credit-card"></i> Buy Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- PRODUCT CONTENT -->
                <div class="backdrop-blur-2xl bg-white/10 border border-white/10 p-10 rounded-3xl shadow-xl">
                    <h2 class="text-3xl font-bold text-pink-400 mb-4">Product Details</h2>
                    <div class="text-gray-300 text-lg leading-relaxed">
                        {!! $product->content !!}
                    </div>
                </div>
            </div>

            <!-- RELATED PRODUCTS -->
            <div class="lg:col-span-1">
                <div class="backdrop-blur-2xl bg-white/10 border border-white/10 p-8 rounded-3xl shadow-xl">
                    <h2 class="text-3xl font-bold text-pink-400 mb-6">Related Products</h2>

                    <div class="space-y-5">
                        @foreach ($relatedProducts as $relatedProduct)
                            <a href="{{ route('product.details', $relatedProduct->slug) }}"
                               class="block bg-white/5 border border-white/10 p-5 rounded-xl hover:bg-white/10 transition shadow-lg">

                                <h3 class="text-lg font-semibold text-white">
                                    {{ $relatedProduct->name }}
                                </h3>

                                <p class="text-sm text-gray-400">
                                    {{ Str::limit($relatedProduct->description, 50) }}
                                </p>

                                <p class="text-xl text-pink-400 font-bold mt-2">
                                    ${{ number_format($relatedProduct->selling_price, 2) }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <x-toast on="cartUpdated" type="success">Success</x-toast>
    <x-toast on="cartUpdateFailed" type="failed">Out of Stock</x-toast>

</section>
