<section class="relative bg-gray-900 text-white overflow-hidden px-6 py-20">
    @section('title', $subcategory->meta_title ?? $subcategory->name . ' - ' . ($subcategory->category->name ?? 'Category') . ' - ' . ($system->website_name ?? 'PvaProseller'))
    @section('description', $subcategory->description ?? 'Explore ' . $subcategory->name . ' in ' . ($subcategory->category->name ?? 'our store') . '. Verified bulk accounts available.')
    @section('keywords', $subcategory->keywords ?? '')
    @section('og_image', image_path($subcategory->image))
    @section('og_type', 'product.group')

    <!-- Glow Background -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-pink-600/30 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[150px]"></div>
    </div>

    <div class="container mx-auto relative  ">

        <!-- BREADCRUMB HEADER -->
        <div class="mb-10 text-center sm:text-left">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white mb-2">
                <span class="bg-gradient-to-r from-pink-500 to-purple-500 bg-clip-text text-transparent">
                   Explore {{ $subcategory->name }}'s
                </span>
            </h1>
            
            @if($subcategory->content)
                <div class="text-gray-300 text-lg leading-relaxed mt-6 max-w-4xl mx-auto sm:mx-0">
                    {!! $subcategory->content !!}
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            @foreach ($products as $product)
                <div
                    class="group rounded-3xl overflow-hidden bg-gray-900/70 border border-white/10 backdrop-blur-xl transition hover:scale-[1.02] hover:shadow-xl">

                    <!-- Image -->
                    <a href="{{ route('product.details', $product->slug) }}">
                        <div class="relative h-48 sm:h-48 overflow-hidden">

                            <img src="{{ image_path($product->subcategory->image) }}" alt="{{ $product->name }}"
                                class="h-full w-full object-cover transition-transform duration-700" />

                            {{-- Featured Badge --}}
                            @if ($product->is_featured)
                                <span class="badge-left">Featured</span>
                            @endif

                            {{-- Offer Badge --}}
                            @if ($product->hasOffer())
                                <span class="badge-right">
                                    {{ $product->discountPercent() }}% OFF
                                </span>
                            @endif

                            {{-- COUNTDOWN OVER IMAGE --}}
                            @if ($product->hasOffer())
                                @php $end = $product->activeOffer()->end_date; @endphp

                                <div class="countdown-overlay" x-data="{ time: '' }" x-init="const end = new Date('{{ $end }}').getTime();
                                setInterval(() => {
                                    const diff = end - Date.now();
                                    if (diff <= 0) return time = 'Offer ended';
                                    const d = Math.floor(diff / 86400000);
                                    const h = Math.floor(diff / 3600000) % 24;
                                    const m = Math.floor(diff / 60000) % 60;
                                    const s = Math.floor(diff / 1000) % 60;
                                    time = `${d}d ${h}h ${m}m ${s}s`;
                                }, 1000);"
                                    x-text="time">
                                </div>
                            @endif

                        </div>
                    </a>


                    <!-- Content -->
                    <div class="p-5 flex flex-col gap-2">

                        <!-- NAME -->
                        <div class="flex items-center justify-center gap-4 overflow-hidden whitespace-nowrap">
                            <h2 class="truncate text-md  font-bold text-cyan-400 max-w-[100%]">
                                {{ $product->name }}
                            </h2>
                        </div>
                        <div class="flex items-center justify-between w-full">
                            <!-- Price (Left) -->
                            <div class="flex items-center gap-2">
                                @if ($product->activeOffer())
                                    <span class="text-green-400 font-bold text-md">
                                        Price: ${{ number_format($product->discountedPrice(), 2) }}
                                    </span>
                                    <span class="text-xs line-through text-gray-400">
                                        ${{ number_format($product->selling_price, 2) }}
                                    </span>
                                @else
                                    <span class="text-pink-400 font-bold text-md">
                                        Price: ${{ number_format($product->selling_price, 2) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Stock (Right) -->
                            <div class="flex items-center">
                                @if ($product->outOfStock())
                                    <span
                                        class="text-red-500 font-normal text-sm border border-red-500 px-1 py-1 rounded-full">
                                        Out Of Stock
                                    </span>
                                @else
                                    <span class="text-pink-400 font-bold text-md">
                                        Stock: {{ $product->stock }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- FEATURES -->
                        @php
                            $features = $product->featureList();
                        @endphp

                        <div class="features-box">
                            <div class="features-grid {{ count($features) > 4 ? 'scrollable' : '' }}">
                                @foreach ($features as $feature)
                                    <div class="feature-pill" title="{{ $feature }}">
                                        {{ $feature }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

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
            @endforeach

            <!-- PAGINATION -->
            <div class="mt-8">
                {{-- Pagination removed --}}
            </div>
        </div>

        <!-- RELATED PRODUCTS SECTION -->
        @if($relatedProducts->count() > 0)
            <div class="mt-24 border-t border-white/10 pt-16">
                <h2 class="text-3xl font-bold text-center text-white mb-12">
                    <span class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
                        You Might Also Like
                    </span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                    @foreach ($relatedProducts as $product)
                        <div class="group rounded-3xl overflow-hidden bg-gray-900/70 border border-white/10 backdrop-blur-xl transition hover:scale-[1.02] hover:shadow-xl">
                            
                            <!-- Image -->
                            <a href="{{ route('product.details', $product->slug) }}">
                                <div class="relative h-48 sm:h-48 overflow-hidden">
                                    <img src="{{ image_path($product->subcategory->image) }}" alt="{{ $product->name }}"
                                        class="h-full w-full object-cover transition-transform duration-700" />

                                    @if ($product->is_featured)
                                        <span class="badge-left">Featured</span>
                                    @endif

                                    @if ($product->hasOffer())
                                        <span class="badge-right">
                                            {{ $product->discountPercent() }}% OFF
                                        </span>
                                    @endif
                                </div>
                            </a>

                            <!-- Content -->
                            <div class="p-5 flex flex-col gap-2">
                                <div class="flex items-center justify-center gap-4 overflow-hidden whitespace-nowrap">
                                    <h2 class="truncate text-md font-bold text-cyan-400 max-w-[100%]">
                                        {{ $product->name }}
                                    </h2>
                                </div>
                                
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center gap-2">
                                        @if ($product->activeOffer())
                                            <span class="text-green-400 font-bold text-md">${{ number_format($product->discountedPrice(), 2) }}</span>
                                            <span class="text-xs line-through text-gray-400">${{ number_format($product->selling_price, 2) }}</span>
                                        @else
                                            <span class="text-pink-400 font-bold text-md">${{ number_format($product->selling_price, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        @if ($product->outOfStock())
                                            <span class="text-red-500 font-normal text-sm border border-red-500 px-1 py-1 rounded-full">Out Of Stock</span>
                                        @else
                                            <span class="text-pink-400 font-bold text-md">Stock: {{ $product->stock }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Features -->
                                @php $features = $product->featureList(); @endphp
                                <div class="features-box">
                                    <div class="features-grid {{ count($features) > 4 ? 'scrollable' : '' }}">
                                        @foreach ($features as $feature)
                                            <div class="feature-pill" title="{{ $feature }}">{{ $feature }}</div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col sm:flex-row gap-3 mt-4 pointer-events-auto">
                                    <div class="flex flex-col flex-1">
                                        <span wire:click="addToCart({{ $product->id }})"
                                            class="w-full flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-full bg-gradient-to-r {{ $product->stock <= 0 ? 'from-cyan-500 to-blue-500' : 'from-pink-500 to-purple-500' }} text-white shadow-lg cursor-pointer hover:scale-105 active:scale-95 transition-all">
                                            {{ $product->stock <= 0 ? 'Pre-Order' : 'Add to Cart' }}
                                        </span>
                                    </div>
                                    @if($product->stock > 0)
                                        <span wire:click="buyNow({{ $product->id }})"
                                            class="flex-1 flex items-center justify-center px-4 py-2 text-xs font-semibold rounded-full bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg cursor-pointer hover:scale-105 active:scale-95 transition-all">
                                            Buy Now
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    <style>
        /* BADGES */
        .badge-left,
        .badge-right {
            position: absolute;
            top: 10px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 999px;
            color: white;
        }

        .badge-left {
            left: 10px;
            background: linear-gradient(to right, #ec4899, #d946ef);
        }

        .badge-right {
            right: 10px;
            background: linear-gradient(to right, #7c3aed, #2563eb);
        }

        /* FEATURES BOX */
        .features-box {
            height: 68px;
            padding: 6px;
            border-radius: 14px;
            position: relative;
            background: rgba(17, 24, 39, 0.65);
            z-index: 0;
            overflow: hidden;
        }

        /* Animated gradient border */
        .features-box::before {
            content: "";
            position: absolute;
            inset: 0;
            padding: 2px;
            border-radius: inherit;
            background: linear-gradient(270deg,
                    #ec4899,
                    #d946ef,
                    #22d3ee,
                    #ec4899);
            background-size: 400% 400%;
            animation: gradient-border 6s ease infinite;

            -webkit-mask:
                linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;

            pointer-events: none;
            z-index: -1;
        }

        @keyframes gradient-border {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        /* 2 rows × 24px pills + 1 gap = ~54px */
        .features-grid.scrollable {
            max-height: 54px;
            overflow-y: auto;
            padding-right: 4px;
            /* space for scrollbar */
        }

        /* Optional: nicer scrollbar */
        .features-grid.scrollable::-webkit-scrollbar {
            width: 3px;
        }

        .features-grid.scrollable::-webkit-scrollbar-thumb {
            background: rgba(34, 211, 238, 0.6);
            border-radius: 999px;
        }

        .feature-pill {
            height: 24px;
            font-size: 11px;
            border-radius: 999px;
            background: rgba(0, 0, 0, .4);
            border: 1px solid rgba(255, 255, 255, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (hover:hover) {
            .feature-pill:hover {
                box-shadow: 0 0 10px rgba(34, 211, 238, .6);
                transform: scale(1.05);
            }
        }

        .countdown-overlay {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);

            padding: 4px 10px;
            font-size: 11px;
            font-weight: 700;

            border-radius: 999px;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(6px);

            color: #22d3ee;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 12px rgba(34, 211, 238, 0.4);

            white-space: nowrap;
            pointer-events: none;
        }

        /* Slightly bigger on desktop */
        @media (min-width: 640px) {
            .countdown-overlay {
                font-size: 12px;
                padding: 5px 12px;
            }
        }
    </style>
</section>
