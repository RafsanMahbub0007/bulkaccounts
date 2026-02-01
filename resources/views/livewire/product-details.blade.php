<section class="relative bg-gray-900 text-white py-16 md:py-20 overflow-hidden">

    <!-- Background Neon Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-32 -left-20 w-[500px] md:w-[600px] h-[500px] md:h-[600px] bg-pink-600/20 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-0 right-0 w-[600px] md:w-[700px] h-[600px] md:h-[700px] bg-purple-600/30 rounded-full blur-[180px]"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-14">

            <!-- LEFT CONTENT -->
            <div class="lg:col-span-2 space-y-8">

                <!-- PRODUCT CARD -->
                <div class="backdrop-blur-2xl bg-white/10 border border-white/10
                            p-6 sm:p-8 md:p-10 rounded-3xl shadow-2xl space-y-6"
                     x-data="{ quantity: @entangle('quantity') }">

                    <!-- TITLE + IMAGE -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                        <img src="{{ image_path($product->subcategory->image) }}" loading="lazy" decoding="async"
                             class="w-20 h-20 rounded-2xl shadow-[0_0_20px_rgba(255,0,100,0.4)] object-cover" />

                        <div>
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-pink-400 drop-shadow-lg">
                                {{ $product->name }}
                            </h1>

                            <p class="text-gray-300 mt-1">
                                Category:
                                <span class="text-white font-semibold">{{ $product->category->name }}</span>
                            </p>

                            <p class="text-gray-300">
                                Sub Category:
                                <span class="text-white font-semibold">{{ $product->subCategory->name }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- STOCK + MIN ORDER -->
                    <div class="flex flex-wrap items-center gap-3">
                        @if($product->stock > 0)
                            <span class="px-4 py-1 rounded-full bg-green-500/20 text-green-400 text-sm font-semibold">
                                In Stock ({{ $product->stock }})
                            </span>
                        @else
                            <span class="px-4 py-1 rounded-full bg-red-500/20 text-red-400 text-sm font-semibold">
                                Out of Stock
                            </span>
                        @endif

                        <span class="px-3 py-1 rounded-full bg-white/5 text-gray-300 text-sm">
                            Min Order: {{ $product->min_order_qty }}
                        </span>
                    </div>

                    <!-- DESCRIPTION -->
                    @if($product->description)
                        <p class="text-gray-300 text-base sm:text-lg leading-relaxed max-w-3xl">
                            {{ $product->description }}
                        </p>
                    @endif

                    <!-- PRICE -->
                    <p class="text-lg sm:text-xl text-gray-300">
                        Price per unit:
                        <span class="block sm:inline font-bold text-pink-400 text-3xl drop-shadow">
                            ${{ number_format($product->selling_price, 2) }}
                        </span>
                    </p>

                    <!-- QUANTITY -->
                    @if($product->stock > 0)
                        <div>
                            <label class="text-lg text-gray-300 block mb-2">Quantity</label>

                            <div class="flex items-center w-fit bg-white/5 border border-white/10 rounded-xl p-1 shadow-lg">

                                <button
                                    @click="quantity = Math.max(quantity - 1, {{ $product->min_order_qty }})"
                                    class="px-4 py-2 bg-gray-800/60 hover:bg-gray-700 rounded-l-lg">
                                    −
                                </button>

                                <input type="number"
                                       x-model.number="quantity"
                                       wire:model="quantity"
                                       min="{{ $product->min_order_qty }}"
                                       max="{{ $product->stock }}"
                                       class="bg-transparent px-3 py-2 w-20 text-center text-xl font-semibold focus:outline-none"/>

                                <button
                                    @click="quantity = Math.min(quantity + 1, {{ $product->stock }})"
                                    class="px-4 py-2 bg-gray-800/60 hover:bg-gray-700 rounded-r-lg">
                                    +
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- TOTAL -->
                    @if($product->stock > 0)
                        <p class="text-xl text-gray-300">
                            Total:
                            <span x-text="'$' + (quantity * {{ $product->selling_price }}).toFixed(2)"
                                  class="font-extrabold text-pink-400 text-3xl"></span>
                        </p>
                    @endif

                    <!-- ACTION BUTTONS -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">

                        @if($product->stock > 0)

                            <form wire:submit.prevent="addToCart" class="w-full sm:w-auto">
                                <button type="submit"
                                    class="w-full sm:w-auto bg-gradient-to-r from-pink-500 to-purple-600
                                           hover:from-pink-600 hover:to-purple-700
                                           px-6 py-3 rounded-xl font-semibold shadow-lg flex justify-center items-center gap-2">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </form>

                            <form wire:submit.prevent="buyNow" class="w-full sm:w-auto">
                                <button type="submit"
                                    class="w-full sm:w-auto bg-gradient-to-r from-green-500 to-teal-600
                                           hover:from-green-600 hover:to-teal-700
                                           px-6 py-3 rounded-xl font-semibold shadow-lg flex justify-center items-center gap-2">
                                    <i class="fas fa-credit-card"></i> Buy Now
                                </button>
                            </form>

                        @else
                            
                            <div class="flex flex-col items-center">
                                <a href="{{ $system->pre_order_link }}"
                                   class="inline-flex justify-center items-center gap-2
                                          rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/30 transition-all duration-300 cursor-pointer
                                          hover:scale-105 active:scale-95
                                          px-8 py-4 font-bold">
                                    <i class="fas fa-rotate"></i> Pre-Order Now
                                </a>
                                <span class="text-xs text-cyan-500 mt-2 font-medium animate-pulse">
                                    Delivery: 24-72 hours
                                </span>
                            </div>

                        @endif
                    </div>
                </div>

                <!-- PRODUCT DETAILS -->
                <div class="backdrop-blur-2xl bg-white/10 border border-white/10
                            p-6 sm:p-8 md:p-10 rounded-3xl shadow-xl">
                    <h2 class="text-2xl sm:text-3xl font-bold text-pink-400 mb-4">
                        Product Details
                    </h2>
                    <div class="text-gray-300 text-base sm:text-lg leading-relaxed">
                        {!! $product->content !!}
                    </div>
                </div>
            </div>

            <!-- RELATED PRODUCTS -->
            <div class="lg:col-span-1">
                <div class="backdrop-blur-2xl bg-white/10 border border-white/10
                            p-6 sm:p-8 rounded-3xl shadow-xl">
                    <h2 class="text-2xl sm:text-3xl font-bold text-pink-400 mb-6">
                        Related Products
                    </h2>

                    <div class="space-y-4">
                        @foreach ($relatedProducts as $relatedProduct)
                            <a href="{{ route('product.details', $relatedProduct->slug) }}"
                               class="block bg-white/5 border border-white/10 p-4 rounded-xl
                                      hover:bg-white/10 transition shadow-lg">
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

    <!-- TOASTS -->
    <x-toast on="cartUpdated" type="success">Success</x-toast>
    <x-toast on="cartUpdateFailed" type="failed">Out of Stock</x-toast>

</section>
