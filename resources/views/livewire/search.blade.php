<div class="min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-900" x-data="{ showFilters: window.innerWidth >= 1024 }">
    <div class="container mx-auto px-4 py-6 md:py-8">
        <!-- Header Section -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent mb-2">
                        Discover Products
                    </h1>
                    <p class="text-gray-400 text-sm md:text-base max-w-2xl">
                        Find verified products with advanced filtering and real-time search
                    </p>
                </div>

                <!-- Results Summary -->
                @if($products->count() > 0)
                <div class="flex items-center gap-3">
                    <div class="hidden md:block h-8 w-px bg-white/10"></div>
                    <div class="text-right">
                        <div class="text-sm text-gray-400">Showing</div>
                        <div class="text-lg font-semibold text-white">
                            {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $totalResults }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Actions Bar -->
            <div class="flex flex-col sm:flex-row gap-3 mb-6">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 rounded-2xl blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input
                                type="text"
                                wire:model.live.debounce.500ms="query"
                                placeholder="Search by name, category, or features..."
                                class="w-full pl-12 pr-4 py-3.5 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500/30 transition-all duration-300 text-sm md:text-base"
                            />
                        </div>
                    </div>
                </div>

                <!-- Filter & Sort Controls -->
                <div class="flex items-center gap-2">
                    <!-- Sort Dropdown -->
                    <div class="relative group" x-data="{ open: false }" @click.away="open = false">
                        <button
                            @click="open = !open"
                            class="flex items-center gap-2 px-4 py-3.5 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl text-white hover:bg-white/10 transition-all duration-300 whitespace-nowrap"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                            </svg>
                            <span class="hidden sm:inline">Sort</span>
                            <span class="capitalize text-sm">{{ str_replace('_', ' ', $sortBy) }}</span>
                            <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-gray-900/95 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl z-50 py-2">
                            @foreach(['newest' => 'Newest First', 'price_low' => 'Price: Low to High', 'price_high' => 'Price: High to Low', 'name' => 'Name A-Z', 'popular' => 'Most Popular'] as $value => $label)
                                <button
                                    wire:click="$set('sortBy', '{{ $value }}')"
                                    @click="open = false"
                                    class="w-full px-4 py-2.5 text-left text-white hover:bg-white/10 transition-colors flex items-center justify-between group"
                                >
                                    <span>{{ $label }}</span>
                                    @if($sortBy === $value)
                                        <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Mobile Filter Toggle -->
                    <button
                        @click="showFilters = !showFilters"
                        class="lg:hidden flex items-center gap-2 px-4 py-3.5 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl text-white hover:bg-white/10 transition-all duration-300 relative"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span class="hidden sm:inline">Filters</span>
                        @if($category || $minPrice || $maxPrice || $inStock)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center text-xs font-bold">
                                {{ ($category?1:0)+($minPrice?1:0)+($maxPrice?1:0)+($inStock?1:0) }}
                            </span>
                        @endif
                    </button>
                </div>
            </div>

            <!-- Active Filters -->
            @if($category || $minPrice || $maxPrice || $inStock)
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-sm text-gray-400">Active filters:</span>
                    <button wire:click="resetFilters" class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors flex items-center gap-1">
                        Clear all
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if($category)
                        @php $cat = $categories->find($category); @endphp
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 text-cyan-300 rounded-full text-sm border border-cyan-500/30">
                            {{ $cat->name ?? 'Category' }}
                            <button wire:click="$set('category', '')" class="hover:text-white transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if($inStock)
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-green-500/20 to-emerald-500/20 text-green-300 rounded-full text-sm border border-green-500/30">
                            In Stock
                            <button wire:click="$set('inStock', false)" class="hover:text-white transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if($minPrice)
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-purple-500/20 to-pink-500/20 text-purple-300 rounded-full text-sm border border-purple-500/30">
                            Min: ${{ $minPrice }}
                            <button wire:click="$set('minPrice', '')" class="hover:text-white transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if($maxPrice)
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-purple-500/20 to-pink-500/20 text-purple-300 rounded-full text-sm border border-purple-500/30">
                            Max: ${{ $maxPrice }}
                            <button wire:click="$set('maxPrice', '')" class="hover:text-white transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-3"
                 x-show="showFilters || window.innerWidth >= 1024"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 -translate-x-4"
                 class="lg:block"
            >
                <div class="bg-gradient-to-br from-gray-900/80 to-black/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6 lg:sticky lg:top-8 shadow-2xl">
                    <!-- Mobile Header -->
                    <div class="flex items-center justify-between mb-6 lg:hidden">
                        <h3 class="text-xl font-semibold text-white">Filter Products</h3>
                        <button @click="showFilters = false" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <label class="block text-white font-medium mb-3 text-sm md:text-base">Category</label>
                        <div class="space-y-2">
                            <button
                                wire:click="$set('category', '')"
                                class="w-full text-left px-4 py-3 rounded-xl transition-all duration-300 flex items-center justify-between group"
                                :class="$wire.category === '' ? 'bg-gradient-to-r from-cyan-500/20 to-blue-500/20 border border-cyan-500/30 text-cyan-300' : 'bg-white/5 hover:bg-white/10 border border-transparent text-gray-300 hover:text-white'"
                            >
                                <span>All Categories</span>
                                @if($category === '')
                                    <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </button>
                            @foreach($categories as $cat)
                                <button
                                    wire:click="$set('category', '{{ $cat->id }}')"
                                    class="w-full text-left px-4 py-3 rounded-xl transition-all duration-300 flex items-center justify-between group"
                                    :class="$wire.category === '{{ $cat->id }}' ? 'bg-gradient-to-r from-cyan-500/20 to-blue-500/20 border border-cyan-500/30 text-cyan-300' : 'bg-white/5 hover:bg-white/10 border border-transparent text-gray-300 hover:text-white'"
                                >
                                    <span>{{ $cat->name }}</span>
                                    @if($category === $cat->id)
                                        <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-white font-medium mb-3 text-sm md:text-base">Price Range</label>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm text-gray-400">Min: <span class="text-white">${{ $minPrice ?: '0' }}</span></span>
                                    <span class="text-sm text-gray-400">Max: <span class="text-white">${{ $maxPrice ?: '1000' }}</span></span>
                                </div>
                                <div class="relative pt-1">
                                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full"
                                             style="width: {{ min(100, (($minPrice ?: 0) + ($maxPrice ?: 1000)) / 20) }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-gray-400 mb-1.5 block">Min Price</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">$</span>
                                        <input
                                            type="number"
                                            wire:model.live.debounce.500ms="minPrice"
                                            placeholder="0"
                                            class="w-full pl-8 pr-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500/30 transition-all duration-300 text-sm"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 mb-1.5 block">Max Price</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">$</span>
                                        <input
                                            type="number"
                                            wire:model.live.debounce.500ms="maxPrice"
                                            placeholder="1000"
                                            class="w-full pl-8 pr-3 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500/30 transition-all duration-300 text-sm"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Filter -->
                    <div class="mb-8">
                        <label class="flex items-center justify-between cursor-pointer group p-3 rounded-xl hover:bg-white/5 transition-colors duration-300">
                            <div class="flex items-center">
                                <div class="relative">
                                    <input
                                        type="checkbox"
                                        wire:model.live="inStock"
                                        class="sr-only peer"
                                    />
                                    <div class="w-10 h-6 bg-white/10 rounded-full peer peer-checked:bg-gradient-to-r peer-checked:from-cyan-500 peer-checked:to-blue-500 transition-colors duration-300"></div>
                                    <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transform peer-checked:translate-x-4 transition-transform duration-300"></div>
                                </div>
                                <span class="ml-3 text-white font-medium">In Stock Only</span>
                            </div>
                            @if($inStock)
                                <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button
                            wire:click="applyFilters"
                            class="w-full py-3.5 bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-cyan-500/25 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filters
                        </button>
                        <button
                            wire:click="resetFilters"
                            class="w-full py-3.5 bg-white/5 border border-white/10 text-white font-medium rounded-xl hover:bg-white/10 hover:border-white/20 transition-all duration-300"
                        >
                            Reset All
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-9">
                <!-- Loading State -->
                <div wire:loading wire:target="query, category, minPrice, maxPrice, inStock, sortBy" class="mb-6">
                    <div class="flex items-center justify-center gap-3 py-4">
                        <div class="w-8 h-8 border-3 border-gray-600 border-t-cyan-400 rounded-full animate-spin"></div>
                        <span class="text-gray-400">Updating results...</span>
                    </div>
                </div>

                @if($products->count() > 0)
                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                        @foreach ($products as $product)
                            <div class="group relative bg-gradient-to-br from-gray-900/50 to-black/50 backdrop-blur-sm border border-white/10 rounded-3xl overflow-hidden hover:border-cyan-500/30 hover:shadow-2xl hover:shadow-cyan-500/10 transition-all duration-500 hover:-translate-y-1">
                                <!-- Image Container -->
                                <a href="{{ route('product.details', $product->slug) }}" class="block relative">
                                    <div class="relative h-36 sm:h-44 md:h-44 overflow-hidden">
                                        <!-- Image -->
                                        <img
                                            src="{{ image_path($product->subcategory->image ?? '') }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                            loading="lazy"
                                        />

                                        <!-- Gradient Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-70 transition-opacity duration-500"></div>

                                        <!-- Badges -->
                                        <div class="absolute top-3 left-3 right-3 flex flex-wrap gap-2">
                                            @if ($product->is_featured)
                                                <span class="px-3 py-1.5 bg-gradient-to-r from-pink-500 to-purple-500 text-white text-xs font-bold rounded-full shadow-lg">
                                                    Featured
                                                </span>
                                            @endif
                                            @if ($product->hasOffer())
                                                <span class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-bold rounded-full shadow-lg">
                                                    -{{ $product->discountPercent() }}%
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Stock Status -->
                                        <div class="absolute bottom-3 left-3">
                                            @if ($product->outOfStock())
                                                <span class="px-3 py-1.5 bg-gradient-to-r from-red-500/90 to-red-600/90 text-white text-xs font-bold rounded-full backdrop-blur-sm">
                                                    Out of Stock
                                                </span>
                                            @else
                                                <span class="px-3 py-1.5 bg-gradient-to-r from-green-500/90 to-emerald-600/90 text-white text-xs font-bold rounded-full backdrop-blur-sm">
                                                    In Stock: {{ $product->stock }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Countdown Timer -->
                                        @if ($product->hasOffer())
                                            @php $offer = $product->activeOffer(); @endphp
                                            <div class="absolute bottom-3 right-3"
                                                x-data="{
                                                    time: '',
                                                    init() {
                                                        const end = new Date('{{ $offer->end_date }}').getTime();
                                                        const update = () => {
                                                            const diff = end - Date.now();
                                                            if (diff <= 0) {
                                                                this.time = 'Ended';
                                                                return;
                                                            }
                                                            const d = Math.floor(diff / 86400000);
                                                            const h = Math.floor(diff / 3600000) % 24;
                                                            const m = Math.floor(diff / 60000) % 60;
                                                            this.time = `${d}d ${h}h`;
                                                        };
                                                        update();
                                                        this.interval = setInterval(update, 60000);
                                                    },
                                                    destroy() {
                                                        clearInterval(this.interval);
                                                    }
                                                }"
                                            >
                                                <div class="px-3 py-1.5 bg-gradient-to-r from-cyan-500/90 to-blue-500/90 text-white text-xs font-bold rounded-full backdrop-blur-sm shadow-lg">
                                                    <span x-text="time"></span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                <!-- Product Info -->
                                <div class="p-4 md:p-5">
                                    <!-- Name -->
                                    <h3 class="text-sm md:text-base font-bold text-white mb-2 line-clamp-1 group-hover:text-cyan-300 transition-colors duration-300">
                                        <a href="{{ route('product.details', $product->slug) }}" class="hover:underline">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <!-- Price -->
                                    <div class="flex items-center gap-2 mb-3">
                                        @if ($product->hasOffer())
                                            <span class="text-lg md:text-xl font-bold text-green-400">
                                                ${{ number_format($product->discountedPrice(), 2) }}
                                            </span>
                                            <span class="text-sm text-gray-400 line-through">
                                                ${{ number_format($product->selling_price, 2) }}
                                            </span>
                                            <span class="text-xs bg-green-500/20 text-green-300 px-2 py-0.5 rounded-full">
                                                Save {{ $product->discountPercent() }}%
                                            </span>
                                        @else
                                            <span class="text-lg md:text-xl font-bold text-cyan-400">
                                                ${{ number_format($product->selling_price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Hover Effect Glow -->
                                <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/0 via-cyan-500/5 to-blue-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none rounded-3xl"></div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($products, 'links'))
                        <div class="mt-8 flex justify-center">
                             {{-- Pagination removed --}}
                        </div>
                    @endif
                @else
                    <!-- No Results -->
                    <div class="text-center py-16 md:py-20 bg-gradient-to-br from-gray-900/50 to-black/50 backdrop-blur-sm border border-white/10 rounded-3xl">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-cyan-500/10 to-blue-500/10 rounded-full mb-6">
                            <svg class="w-12 h-12 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-3">No Products Found</h3>
                        <p class="text-gray-400 mb-8 max-w-md mx-auto">
                            We couldn't find any products matching your criteria. Try adjusting your filters or search terms.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button
                                wire:click="resetFilters"
                                class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-cyan-500/25 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300"
                            >
                                Reset All Filters
                            </button>
                            <button
                                wire:click="$set('query', '')"
                                class="px-6 py-3 bg-white/5 border border-white/10 text-white font-medium rounded-xl hover:bg-white/10 hover:border-white/20 transition-all duration-300"
                            >
                                Clear Search
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Initialize Alpine.js data
        if (!window.alpineInitialized) {
            window.alpineInitialized = true;

            // Handle mobile filter toggle
            Livewire.on('toggle-filters', () => {
                const showFilters = document.querySelector('[x-data]').__x.$data.showFilters;
                document.querySelector('[x-data]').__x.$data.showFilters = !showFilters;
            });

            // Close filters on mobile when clicking outside
            document.addEventListener('click', (e) => {
                const filtersPanel = document.querySelector('[x-show*="showFilters"]');
                const toggleButton = document.querySelector('[wire\\:click*="toggleFilters"]') ||
                                   document.querySelector('[onclick*="showFilters"]');

                if (window.innerWidth < 1024 &&
                    filtersPanel &&
                    !filtersPanel.contains(e.target) &&
                    toggleButton &&
                    !toggleButton.contains(e.target)) {

                    const alpineData = document.querySelector('[x-data]').__x.$data;
                    if (alpineData.showFilters) {
                        alpineData.showFilters = false;
                    }
                }
            });

            // Update showFilters on window resize
            window.addEventListener('resize', () => {
                const alpineData = document.querySelector('[x-data]').__x.$data;
                alpineData.showFilters = window.innerWidth >= 1024;
            });
        }
    });

    // Smooth scroll to top on page change
    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        succeed(() => {
            if (component.uri === window.location.pathname) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
</script>
@endpush
@push('style')
<style>
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #06b6d4, #3b82f6);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #0891b2, #2563eb);
    }

    /* Selection Color */
    ::selection {
        background: rgba(6, 182, 212, 0.3);
        color: white;
    }

    /* Smooth transitions */
    * {
        scroll-behavior: smooth;
    }

    /* Loading skeleton animation */
    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: calc(200px + 100%) 0; }
    }

    .loading-skeleton {
        background: linear-gradient(90deg, rgba(255,255,255,0.06) 25%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.06) 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    /* Line clamp utilities */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Responsive breakpoint for extra small */
    @media (max-width: 475px) {
        .xs\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    /* Glass morphism effect */
    .glass-effect {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Gradient text animation */
    .gradient-text {
        background: linear-gradient(90deg, #06b6d4, #3b82f6, #8b5cf6);
        background-size: 200% auto;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradient 3s ease infinite;
    }

    @keyframes gradient {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
</style>
@endpush
