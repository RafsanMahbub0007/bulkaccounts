<section class="bg-gray-900 text-white relative overflow-hidden px-6 py-20"> <!-- SINGLE ROOT START -->
    <div class="container mx-auto py-10">

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-12">
            @foreach ($products as $product)
                <article
                    class="relative group rounded-3xl overflow-hidden shadow-xl backdrop-blur-md border border-white/10 bg-gradient-to-br from-gray-800/60 to-gray-900/80 transition-transform duration-500 hover:scale-[1.03] hover:shadow-[0_0_50px_rgba(255,255,255,0.1)]">

                    <!-- Floating Gradient Lights -->
                    <div class="absolute -top-10 -left-10 w-36 h-36 bg-gradient-to-br from-pink-400/30 to-transparent rounded-full blur-3xl animate-blob"></div>
                    <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-gradient-to-tr from-cyan-400/30 to-transparent rounded-full blur-3xl animate-blob animation-delay-2000"></div>

                    <!-- Product Image -->
                    <div class="relative w-full h-48 overflow-hidden rounded-t-3xl">
                        <img src="{{ image_path($product->subcategory->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             loading="lazy">

                        @if($product->is_featured)
                            <span class="absolute top-3 left-3 bg-gradient-to-r from-pink-500 to-fuchsia-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                Featured
                            </span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex flex-col justify-between gap-4 text-gray-200">
                        <div>
                            <h3 class="text-2xl font-bold mb-2 bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                                {{ $product->name }}
                            </h3>
                            <span class="text-xl font-bold text-pink-400">${{ number_format($product->selling_price ?? 49.99, 2) }}</span>
                            <p class="text-gray-400 text-sm leading-relaxed mt-2">
                               @foreach ($product->featureList() as $feature)
                                <span
                                    class="relative text-xs px-3 py-1 rounded-full font-semibold text-white
               bg-gray-900/30 border border-white/20
               shadow-[0_0_10px_rgba(255,255,255,0.2)]
               before:absolute before:inset-0 before:rounded-full
               before:bg-gradient-to-r before:from-pink-500 before:to-purple-500
               before:blur-lg before:opacity-50 before:z-[-1]
               hover:shadow-[0_0_20px_rgba(255,255,255,0.5)]
               transition-all duration-300">
                                    {{ $feature }}
                                </span>
                            @endforeach
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 mt-4 w-full">
                            <!-- Add to Cart -->
                            <form wire:submit.prevent="addToCart({{ $product->id }})" class="flex-1">
                                <input type="hidden" name="quantity" wire:model="quantity" />

                                <button type="submit"
                                    class="w-full text-center px-4 py-2 text-sm font-semibold rounded-xl bg-gradient-to-r from-pink-500 to-purple-500 hover:from-pink-400 hover:to-purple-400 text-white shadow-lg shadow-pink-500/30 transition duration-300">
                                    <i class="fas fa-cart-plus mr-2"></i> Add to Cart
                                </button>
                            </form>

                            <!-- Buy Now -->
                            <form wire:submit.prevent="buyNow({{ $product->id }})" class="flex-1">
                                <input type="hidden" name="quantity" wire:model="quantity" />

                                <button type="submit"
                                    class="w-full text-center px-4 py-2 text-sm font-semibold rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white shadow-lg shadow-cyan-500/30 transition duration-300">
                                    <i class="fas fa-credit-card mr-2"></i> Buy Now
                                </button>
                            </form>
                        </div>

                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2 mt-3">
                            @if($product->is_new)
                                <span class="text-xs bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-3 py-1 rounded-full shadow-md">New</span>
                            @endif
                            @if($product->is_trending)
                                <span class="text-xs bg-gradient-to-r from-green-400 to-teal-400 text-white px-3 py-1 rounded-full shadow-md">Trending</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
    <style>
        @keyframes blob {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            33% { transform: translate3d(20px, -15px, 0) scale(1.1); }
            66% { transform: translate3d(-20px, 15px, 0) scale(0.95); }
        }
        .animate-blob { animation: blob 8s infinite ease-in-out; }
        .animation-delay-2000 { animation-delay: 2s; }
    </style>
</section> <!-- SINGLE ROOT END -->

