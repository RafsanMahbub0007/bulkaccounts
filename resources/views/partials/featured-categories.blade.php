<section
    class="relative py-20 sm:py-28 px-4 bg-gradient-to-br from-gray-950 via-gray-900 to-black text-white overflow-hidden">

    <!-- Background Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-pink-500/30 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-cyan-400/25 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto">

        <!-- Header -->
        <header class="text-center max-w-3xl mx-auto mb-16">
            <h2
                class="text-4xl sm:text-6xl font-extrabold bg-gradient-to-r from-pink-400 via-purple-400 to-cyan-400 bg-clip-text text-transparent">
                Featured Products
            </h2>
            <p class="mt-4 text-gray-300 text-lg sm:text-xl">
                Handpicked products to elevate your digital presence.
            </p>
        </header>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6">
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
                        <div class="flex items-center justify-center gap-10 overflow-hidden whitespace-nowrap">
                            <h2 class="truncate text-lg  font-semibold text-cyan-400 max-w-[100%]">
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
                                        class="text-red-500 font-semibold text-sm border border-red-500 px-2 py-1 rounded-full">
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
    </div>
</section>
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
