<section class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    @section('title', $product->meta_title ?? $product->name . ' - ' . ($product->category->name ?? 'Product') . ' - ' . ($system->website_name ?? 'PvaProseller'))
    @section('description', $product->description ?? Str::limit(strip_tags($product->content ?? 'Buy ' . $product->name . ' at best prices.'), 150))
    @section('keywords', $product->keywords ?? '')
    @section('og_image', image_path($product->product_image ?? $product->subcategory->image))
    @section('og_type', 'product')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Main Product Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                
                <!-- Product Image -->
                <div class="p-8 flex items-center justify-center bg-gray-100 dark:bg-gray-700/50">
                    <div class="relative w-full max-w-md group">
                        <img src="{{ image_path($product->subcategory->image) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-auto object-contain rounded-xl shadow-lg transition duration-500 group-hover:scale-105"
                             loading="lazy">
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-8 md:p-10 flex flex-col justify-center space-y-6">
                    
                    <!-- Title & Category -->
                    <div>
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
                            <span class="font-medium text-pink-500">{{ $product->category->name }}</span>
                            <i class="fas fa-chevron-right text-xs"></i>
                            <span class="font-medium text-cyan-500">{{ $product->subCategory->name }}</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white leading-tight">
                            {{ $product->name }}
                        </h1>
                    </div>

                    <!-- Short Description -->
                    @if($product->description)
                        <p class="text-gray-600 dark:text-gray-300 border-l-4 border-pink-500 pl-4 italic">
                            {{ $product->description }}
                        </p>
                    @endif

                    <!-- Price & Stock -->
                    <div class="flex flex-wrap items-center gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Price</p>
                            <div class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-500">
                                ${{ number_format($product->selling_price, 2) }}
                            </div>
                        </div>

                        <div class="h-10 w-px bg-gray-300 dark:bg-gray-600"></div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                            @if($product->stock > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400">
                                    <i class="fas fa-check-circle mr-2"></i> In Stock ({{ $product->stock }})
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400">
                                    <i class="fas fa-times-circle mr-2"></i> Out of Stock
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Add to Cart Section -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700"
                         x-data="{ 
                             quantity: @entangle('quantity'),
                             minQty: {{ $product->min_order_qty > 0 ? $product->min_order_qty : 1 }},
                             maxQty: {{ $product->stock }}
                         }"
                         x-init="$watch('quantity', value => {
                             if (value === '' || value === null) return;
                             if (value < minQty) quantity = minQty;
                             if (value > maxQty) quantity = maxQty;
                         })">

                        @if($product->stock > 0)
                            <div class="flex flex-col sm:flex-row items-center gap-4 mb-4">
                                <!-- Quantity -->
                                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800">
                                    <button @click="quantity > minQty ? quantity-- : null"
                                            :class="{ 'opacity-50 cursor-not-allowed': quantity <= minQty }"
                                            class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-pink-500 transition">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" 
                                           x-model.number="quantity"
                                           class="w-16 text-center bg-transparent border-none focus:ring-0 text-gray-900 dark:text-white font-bold"
                                           :min="minQty" :max="maxQty">
                                    <button @click="quantity < maxQty ? quantity++ : null"
                                            :class="{ 'opacity-50 cursor-not-allowed': quantity >= maxQty }"
                                            class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-pink-500 transition">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                <!-- Total -->
                                <div class="text-gray-700 dark:text-gray-300 font-semibold">
                                    Total: <span class="text-xl text-gray-900 dark:text-white font-bold" x-text="'$' + (quantity * {{ $product->selling_price }}).toFixed(2)"></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <button wire:click="addToCart"
                                        class="px-6 py-3 bg-gray-900 dark:bg-gray-700 text-white font-bold rounded-xl hover:bg-gray-800 dark:hover:bg-gray-600 transition shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                                <button wire:click="buyNow"
                                        class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-bold rounded-xl hover:shadow-cyan-500/30 hover:scale-[1.02] transition shadow-lg flex items-center justify-center gap-2">
                                    <i class="fas fa-bolt"></i> Buy Now
                                </button>
                            </div>
                        @else
                            <!-- Pre-Order -->
                            <a href="{{ $system->pre_order_link ?? '#' }}" target="_blank"
                               class="block w-full text-center px-6 py-4 bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-bold rounded-xl shadow-lg hover:shadow-cyan-500/30 hover:scale-[1.02] transition">
                                <i class="fas fa-clock mr-2"></i> Pre-Order Now
                            </a>
                            <p class="text-center text-cyan-500 text-sm mt-2 animate-pulse">
                                Estimated Delivery: 24-72 hours
                            </p>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <!-- Details & Features Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-12">
            
            <!-- Long Description -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                        <span class="w-1 h-8 bg-pink-500 rounded-full"></span>
                        Product Description
                    </h2>
                    <div class="prose prose-lg dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                        {!! $product->content !!}
                    </div>
                </div>
            </div>

            <!-- Trust Badges (Sidebar) -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Why Choose Us?</h3>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center text-green-600 dark:text-green-400">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">Instant Delivery</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">Secure Payment</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
                                <i class="fas fa-headset"></i>
                            </div>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">24/7 Support</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-10">
                    <span class="bg-gradient-to-r from-cyan-500 to-blue-500 bg-clip-text text-transparent">
                        Related Products
                    </span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($relatedProducts as $product)
                        <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-2xl transition duration-300">
                            
                            <!-- Image -->
                            <a href="{{ route('product.details', $product->slug) }}" class="block relative h-48 overflow-hidden bg-gray-100 dark:bg-gray-700">
                                <img src="{{ image_path($product->subcategory->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                
                                @if ($product->is_featured)
                                    <span class="absolute top-3 left-3 px-3 py-1 bg-gradient-to-r from-pink-500 to-purple-500 text-white text-xs font-bold rounded-full shadow-lg">
                                        Featured
                                    </span>
                                @endif

                                @if ($product->hasOffer())
                                    <span class="absolute top-3 right-3 px-3 py-1 bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-xs font-bold rounded-full shadow-lg">
                                        {{ $product->discountPercent() }}% OFF
                                    </span>
                                @endif
                            </a>

                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="font-bold text-gray-900 dark:text-white truncate mb-2" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h3>

                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        @if ($product->activeOffer())
                                            <span class="text-green-500 font-bold">${{ number_format($product->discountedPrice(), 2) }}</span>
                                            <span class="text-xs text-gray-400 line-through ml-1">${{ number_format($product->selling_price, 2) }}</span>
                                        @else
                                            <span class="text-pink-500 font-bold">${{ number_format($product->selling_price, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $product->stock }} in stock
                                    </div>
                                </div>

                                <a href="{{ route('product.details', $product->slug) }}" 
                                   class="block w-full py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center text-sm font-bold rounded-lg hover:bg-pink-500 hover:text-white transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    @push('schema')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": @json($product->name),
      "image": @json(image_path($product->product_image ?? $product->subcategory->image)),
      "description": @json($product->description ?? Str::limit(strip_tags($product->content), 150)),
      "sku": @json($product->slug),
      "offers": {
        "@type": "Offer",
        "url": @json(url()->current()),
        "priceCurrency": "USD",
        "price": "{{ $product->selling_price }}",
        "availability": "https://schema.org/{{ $product->stock > 0 ? 'InStock' : 'OutOfStock' }}"
      }
    }
    </script>
    @endpush

</section>
