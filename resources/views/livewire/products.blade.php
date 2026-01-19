<section class="relative bg-gray-900 text-white overflow-hidden px-6 py-20">

    <!-- Glow Background -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-pink-600/30 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[150px]"></div>
    </div>

    <div class="container mx-auto relative z-10 py-10">

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

            <!-- SIDEBAR FILTER -->
            <div class="lg:col-span-1">
                <div class="backdrop-blur-2xl bg-white/10 border border-white/10 p-6 rounded-2xl shadow-xl">
                    <h3 class="text-xl font-semibold text-pink-400 mb-4">Filter by Category</h3>

                    @foreach ($categories as $category)
                        <label class="flex items-center gap-3 mb-3 cursor-pointer">
                            <x-input type="checkbox" wire:model.live="category" value="{{ $category->id }}"
                                class="rounded-full border-pink-400 focus:ring-pink-500" />
                            <span class="text-gray-200">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- PRODUCTS -->
            <div class="lg:col-span-4">

                <!-- SEARCH + SORT -->
                <div class="flex justify-between items-center mb-8">

                    <x-input type="text" class="w-1/2 p-3 rounded-xl bg-gray-800 border-gray-700 text-white"
                        placeholder="Search products..." wire:model.live.debounce.500ms="search" />

                    <x-select wire:model.live="sortDirection"
                        class="bg-gray-800 border-gray-700 text-white rounded-xl p-3">
                        <option value="asc">Price: Low → High</option>
                        <option value="desc">Price: High → Low</option>
                    </x-select>
                </div>

                <!-- PRODUCT GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-3">
                    @foreach ($products as $product)
                        <div
                            class="group rounded-3xl overflow-hidden bg-gray-900/70 border border-white/10 backdrop-blur-xl transition hover:scale-[1.02] hover:shadow-xl">

                            <!-- Image -->
                            <a href="{{ route('product.details', $product->slug) }}">
                                <div class="relative h-48 sm:h-48 overflow-hidden">

                                    <img src="{{ image_path($product->subcategory->image) }}" alt="{{ $product->name }}" loading="lazy" decoding="async"
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
                                @if ($product->outOfStock())
                                    <div class="mt-4 pointer-events-auto">
                                        <a href="{{ $system->pre_order_link ?? '#' }}"
                                            class="group relative w-full sm:w-auto
              flex items-center justify-center gap-2
              px-5 sm:px-6 py-2.5
              text-sm sm:text-base font-semibold
              whitespace-nowrap
              rounded-full
              bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-500
              text-white
              shadow-lg shadow-blue-500/30
              transition-all duration-300
              hover:shadow-xl hover:shadow-blue-500/40
              hover:-translate-y-0.5
              active:translate-y-0
              focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-gray-900">

                                            <!-- Icon -->
                                            <i class="fa-solid fa-clock-rotate-left text-sm opacity-90"></i>

                                            <!-- Text -->
                                            <span>Pre-Order Now</span>
                                        </a>
                                    </div>
                                @else
                                    @livewire('add-to-cart', ['productId' => $product->id], key($product->id))
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- PAGINATION -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
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
