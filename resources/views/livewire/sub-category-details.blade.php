<section class="relative bg-gray-900 text-white overflow-hidden px-6 py-20">

    @section('title', $subcategory->meta_title ?? $subcategory->name . ' - ' . ($subcategory->category->name ?? 'Category') . ' - ' . ($system->website_name ?? 'PvaProseller'))

    @section('description', $subcategory->description ?? 'Explore ' . $subcategory->name . ' in ' . ($subcategory->category->name ?? 'our store') . '. Verified bulk accounts available.')

    @section('keywords', $subcategory->keywords ?? '')

    @section('og_image', image_path($subcategory->image))

    @section('og_type', 'product.group')


    <!-- =========================================================
         GLOW BACKGROUND
    ========================================================== -->

    <div class="absolute inset-0 pointer-events-none">

        <div
            class="absolute -top-20 -left-20 w-96 h-96 bg-pink-600/30 rounded-full blur-[120px]">
        </div>

        <div
            class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[150px]">
        </div>

    </div>


    <div class="container mx-auto relative">


        <!-- =====================================================
             HEADER
        ====================================================== -->

        <div class="mb-10 text-center sm:text-left">

            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white mb-2">

                <span
                    class="bg-gradient-to-r from-pink-500 to-purple-500 bg-clip-text text-transparent">
                    Explore {{ $subcategory->name }}'s
                </span>

            </h1>

        </div>


        <!-- =====================================================
             PRODUCTS GRID
        ====================================================== -->

        <div
            class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">

            @foreach ($products as $product)

                <div
                    class="group rounded-3xl overflow-hidden bg-gray-900/70 border border-white/10 backdrop-blur-xl transition hover:scale-[1.02] hover:shadow-xl flex flex-col">


                    <!-- =================================================
                         PRODUCT IMAGE
                    ================================================== -->

                    <a href="{{ route('product.details', $product->slug) }}">

                        <div class="relative h-48 sm:h-48 overflow-hidden">

                            <img
                                src="{{ image_path($product->subcategory->image) }}"
                                alt="{{ $product->name }}"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />


                            <!-- Featured Badge -->

                            @if ($product->is_featured)

                                <span class="badge-left">
                                    Featured
                                </span>

                            @endif


                            <!-- Offer Badge -->

                            @if ($product->hasOffer())

                                <span class="badge-right">
                                    {{ $product->discountPercent() }}% OFF
                                </span>

                            @endif


                            <!-- Countdown -->

                            @if ($product->hasOffer())

                                @php
                                    $end = $product->activeOffer()->end_date;
                                @endphp

                                <div
                                    class="countdown-overlay"
                                    x-data="{ time: '' }"
                                    x-init="
                                        const end = new Date('{{ $end }}').getTime();

                                        const updateCountdown = () => {
                                            const diff = end - Date.now();

                                            if (diff <= 0) {
                                                time = 'Offer ended';
                                                return;
                                            }

                                            const d = Math.floor(diff / 86400000);
                                            const h = Math.floor(diff / 3600000) % 24;
                                            const m = Math.floor(diff / 60000) % 60;
                                            const s = Math.floor(diff / 1000) % 60;

                                            time = `${d}d ${h}h ${m}m ${s}s`;
                                        };

                                        updateCountdown();

                                        const interval = setInterval(updateCountdown, 1000);

                                        $el.addEventListener('alpine:destroy', () => {
                                            clearInterval(interval);
                                        });
                                    "
                                    x-text="time">
                                </div>

                            @endif

                        </div>

                    </a>


                    <!-- =================================================
                         PRODUCT CONTENT
                    ================================================== -->

                    <div class="p-5 flex flex-col gap-2 flex-1">


                        <!-- =================================================
                             PRODUCT NAME
                        ================================================== -->

                        <div
                            class="flex items-center justify-center w-full min-h-[48px]">

                            <h2
                                class="product-name text-sm sm:text-base font-bold text-cyan-400 text-center leading-6 break-words line-clamp-2"
                                title="{{ $product->name }}">

                                {{ $product->name }}

                            </h2>

                        </div>


                        <!-- =================================================
                             PRICE + STOCK
                        ================================================== -->

                        <div
                            class="flex items-center justify-between w-full gap-2">


                            <!-- Price -->

                            <div class="flex items-center gap-2 min-w-0">

                                @if ($product->activeOffer())

                                    <span
                                        class="text-green-400 font-bold text-sm sm:text-md whitespace-nowrap">

                                        Price:
                                        ${{ number_format($product->discountedPrice(), 2) }}

                                    </span>

                                    <span
                                        class="text-xs line-through text-gray-400 whitespace-nowrap">

                                        ${{ number_format($product->selling_price, 2) }}

                                    </span>

                                @else

                                    <span
                                        class="text-pink-400 font-bold text-sm sm:text-md whitespace-nowrap">

                                        Price:
                                        ${{ number_format($product->selling_price, 2) }}

                                    </span>

                                @endif

                            </div>


                            <!-- Stock -->

                            <div class="flex items-center shrink-0">

                                @if ($product->outOfStock())

                                    <span
                                        class="text-red-500 font-normal text-xs sm:text-sm border border-red-500 px-2 py-1 rounded-full whitespace-nowrap">

                                        Out Of Stock

                                    </span>

                                @else

                                    <span
                                        class="text-pink-400 font-bold text-sm sm:text-md whitespace-nowrap">

                                        Stock:
                                        {{ $product->stock }}

                                    </span>

                                @endif

                            </div>

                        </div>


                        <!-- =================================================
                             FEATURES
                        ================================================== -->

                        @php
                            $features = $product->featureList();
                        @endphp

                        <div class="features-box">

                            <div
                                class="features-grid {{ count($features) > 4 ? 'scrollable' : '' }}">

                                @foreach ($features as $feature)

                                    <div
                                        class="feature-pill"
                                        title="{{ $feature }}">

                                        {{ $feature }}

                                    </div>

                                @endforeach

                            </div>

                        </div>


                        <!-- =================================================
                             CART / PREORDER
                        ================================================== -->

                        <div
                            class="flex flex-col sm:flex-row gap-3 mt-4 pointer-events-auto">


                            <!-- Add To Cart / Pre-Order -->

                            <div class="flex flex-col flex-1">

                                <span
                                    wire:click="addToCart({{ $product->id }})"
                                    @if ($product->stock > 0)
                                        data-add-to-cart-trigger
                                    @endif
                                    class="w-full
                                        flex items-center justify-center
                                        px-4 sm:px-5 py-2 sm:py-2.5
                                        text-[13px] sm:text-sm font-semibold
                                        whitespace-nowrap
                                        rounded-full
                                        bg-gradient-to-r
                                        {{ $product->stock <= 0
                                            ? 'from-cyan-500 to-blue-500'
                                            : 'from-pink-500 to-purple-500' }}
                                        text-white
                                        shadow-lg
                                        {{ $product->stock <= 0
                                            ? 'shadow-cyan-500/30'
                                            : 'shadow-pink-500/30' }}
                                        transition-all duration-300
                                        cursor-pointer
                                        hover:scale-105
                                        active:scale-95">

                                    @if ($product->stock <= 0)

                                        <span
                                            class="flex items-center gap-2">

                                            <i class="fas fa-rotate"></i>

                                            Pre-Order Now

                                        </span>

                                    @else

                                        Add to Cart

                                    @endif

                                </span>


                                @if ($product->stock <= 0)

                                    <span
                                        class="text-[10px] sm:text-xs text-cyan-500 text-center mt-1 font-medium animate-pulse">

                                        Delivery: 24 hours

                                    </span>

                                @endif

                            </div>


                            <!-- Buy Now -->

                            @if ($product->stock > 0)

                                <span
                                    wire:click="buyNow({{ $product->id }})"
                                    class="flex-1 min-w-0
                                        flex items-center justify-center
                                        px-4 sm:px-5 py-2 sm:py-2.5
                                        text-[13px] sm:text-sm font-semibold
                                        whitespace-nowrap
                                        rounded-full
                                        bg-gradient-to-r
                                        from-cyan-500 to-blue-500
                                        text-white
                                        shadow-lg shadow-cyan-500/30
                                        transition-all duration-300
                                        cursor-pointer
                                        hover:scale-105
                                        active:scale-95">

                                    Buy&nbsp;Now

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        <!-- =====================================================
             SUBCATEGORY CONTENT
        ====================================================== -->

        @if ($subcategory->content)

            <div
                class="prose prose-invert max-w-none mt-12">

                {!! $subcategory->content !!}

            </div>

        @endif


        <!-- =====================================================
             RELATED PRODUCTS
        ====================================================== -->

        @if ($relatedProducts->count() > 0)

            <div
                class="mt-24 border-t border-white/10 pt-16">


                <!-- Related Heading -->

                <h2
                    class="text-3xl font-bold text-center text-white mb-12">

                    <span
                        class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">

                        You Might Also Like

                    </span>

                </h2>


                <!-- Related Products Grid -->

                <div
                    class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">

                    @foreach ($relatedProducts as $product)

                        <div
                            class="group rounded-3xl overflow-hidden bg-gray-900/70 border border-white/10 backdrop-blur-xl transition hover:scale-[1.02] hover:shadow-xl flex flex-col">


                            <!-- =================================================
                                 RELATED PRODUCT IMAGE
                            ================================================== -->

                            <a
                                href="{{ route('product.details', $product->slug) }}">

                                <div
                                    class="relative h-48 sm:h-48 overflow-hidden">

                                    <img
                                        src="{{ image_path($product->subcategory->image) }}"
                                        alt="{{ $product->name }}"
                                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />


                                    <!-- Featured -->

                                    @if ($product->is_featured)

                                        <span class="badge-left">
                                            Featured
                                        </span>

                                    @endif


                                    <!-- Offer -->

                                    @if ($product->hasOffer())

                                        <span class="badge-right">
                                            {{ $product->discountPercent() }}% OFF
                                        </span>

                                    @endif

                                </div>

                            </a>


                            <!-- =================================================
                                 RELATED CONTENT
                            ================================================== -->

                            <div
                                class="p-5 flex flex-col gap-2 flex-1">


                                <!-- Related Product Name -->

                                <div
                                    class="flex items-center justify-center w-full min-h-[48px]">

                                    <h2
                                        class="product-name text-sm sm:text-base font-bold text-cyan-400 text-center leading-6 break-words line-clamp-2"
                                        title="{{ $product->name }}">

                                        {{ $product->name }}

                                    </h2>

                                </div>


                                <!-- Price + Stock -->

                                <div
                                    class="flex items-center justify-between w-full gap-2">


                                    <!-- Price -->

                                    <div
                                        class="flex items-center gap-2 min-w-0">

                                        @if ($product->activeOffer())

                                            <span
                                                class="text-green-400 font-bold text-sm sm:text-md whitespace-nowrap">

                                                ${{ number_format($product->discountedPrice(), 2) }}

                                            </span>

                                            <span
                                                class="text-xs line-through text-gray-400 whitespace-nowrap">

                                                ${{ number_format($product->selling_price, 2) }}

                                            </span>

                                        @else

                                            <span
                                                class="text-pink-400 font-bold text-sm sm:text-md whitespace-nowrap">

                                                ${{ number_format($product->selling_price, 2) }}

                                            </span>

                                        @endif

                                    </div>


                                    <!-- Stock -->

                                    <div
                                        class="flex items-center shrink-0">

                                        @if ($product->outOfStock())

                                            <span
                                                class="text-red-500 font-normal text-xs sm:text-sm border border-red-500 px-2 py-1 rounded-full whitespace-nowrap">

                                                Out Of Stock

                                            </span>

                                        @else

                                            <span
                                                class="text-pink-400 font-bold text-sm sm:text-md whitespace-nowrap">

                                                Stock:
                                                {{ $product->stock }}

                                            </span>

                                        @endif

                                    </div>

                                </div>


                                <!-- =================================================
                                     RELATED FEATURES
                                ================================================== -->

                                @php
                                    $features = $product->featureList();
                                @endphp

                                <div class="features-box">

                                    <div
                                        class="features-grid {{ count($features) > 4 ? 'scrollable' : '' }}">

                                        @foreach ($features as $feature)

                                            <div
                                                class="feature-pill"
                                                title="{{ $feature }}">

                                                {{ $feature }}

                                            </div>

                                        @endforeach

                                    </div>

                                </div>


                                <!-- =================================================
                                     RELATED ACTIONS
                                ================================================== -->

                                <div
                                    class="flex flex-col sm:flex-row gap-3 mt-4 pointer-events-auto">


                                    <!-- Add To Cart / Preorder -->

                                    <div
                                        class="flex flex-col flex-1">

                                        <span
                                            wire:click="addToCart({{ $product->id }})"
                                            @if ($product->stock > 0)
                                                data-add-to-cart-trigger
                                            @endif
                                            class="w-full
                                                flex items-center justify-center
                                                px-4 py-2
                                                text-xs font-semibold
                                                rounded-full
                                                bg-gradient-to-r
                                                {{ $product->stock <= 0
                                                    ? 'from-cyan-500 to-blue-500'
                                                    : 'from-pink-500 to-purple-500' }}
                                                text-white
                                                shadow-lg
                                                cursor-pointer
                                                hover:scale-105
                                                active:scale-95
                                                transition-all">

                                            @if ($product->stock <= 0)

                                                <span
                                                    class="flex items-center gap-2">

                                                    <i
                                                        class="fas fa-rotate">
                                                    </i>

                                                    Pre-Order

                                                </span>

                                            @else

                                                Add to Cart

                                            @endif

                                        </span>

                                    </div>


                                    <!-- Buy Now -->

                                    @if ($product->stock > 0)

                                        <span
                                            wire:click="buyNow({{ $product->id }})"
                                            class="flex-1
                                                flex items-center justify-center
                                                px-4 py-2
                                                text-xs font-semibold
                                                rounded-full
                                                bg-gradient-to-r
                                                from-cyan-500 to-blue-500
                                                text-white
                                                shadow-lg
                                                cursor-pointer
                                                hover:scale-105
                                                active:scale-95
                                                transition-all">

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


    <!-- =========================================================
         STYLES
    ========================================================== -->

    <style>

        /* =========================================================
           BADGES
        ========================================================== */

        .badge-left,
        .badge-right {

            position: absolute;

            top: 10px;

            padding: 4px 10px;

            font-size: 11px;

            font-weight: 700;

            border-radius: 999px;

            color: white;

            z-index: 5;

        }


        .badge-left {

            left: 10px;

            background:
                linear-gradient(
                    to right,
                    #ec4899,
                    #d946ef
                );

        }


        .badge-right {

            right: 10px;

            background:
                linear-gradient(
                    to right,
                    #7c3aed,
                    #2563eb
                );

        }


        /* =========================================================
           PRODUCT NAME
        ========================================================== */

        .product-name {

            width: 100%;

            overflow-wrap: anywhere;

            word-break: break-word;

        }


        /*
         * If Tailwind's line-clamp utility is not enabled,
         * this CSS guarantees the two-line behavior.
         */

        .product-name {

            display: -webkit-box;

            -webkit-box-orient: vertical;

            -webkit-line-clamp: 2;

            overflow: hidden;

        }


        /* =========================================================
           FEATURES BOX
        ========================================================== */

        .features-box {

            height: 68px;

            padding: 6px;

            border-radius: 14px;

            position: relative;

            background:
                rgba(
                    17,
                    24,
                    39,
                    0.65
                );

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

            background:
                linear-gradient(
                    270deg,
                    #ec4899,
                    #d946ef,
                    #22d3ee,
                    #ec4899
                );

            background-size: 400% 400%;

            animation:
                gradient-border
                6s ease infinite;

            -webkit-mask:
                linear-gradient(
                    #fff 0 0
                ) content-box,
                linear-gradient(
                    #fff 0 0
                );

            -webkit-mask-composite: xor;

            mask-composite: exclude;

            pointer-events: none;

            z-index: -1;

        }


        /* =========================================================
           GRADIENT BORDER ANIMATION
        ========================================================== */

        @keyframes gradient-border {

            0% {

                background-position:
                    0% 50%;

            }

            50% {

                background-position:
                    100% 50%;

            }

            100% {

                background-position:
                    0% 50%;

            }

        }


        /* =========================================================
           FEATURES GRID
        ========================================================== */

        .features-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 6px;

        }


        /* =========================================================
           SCROLLABLE FEATURES
        ========================================================== */

        .features-grid.scrollable {

            max-height: 54px;

            overflow-y: auto;

            padding-right: 4px;

        }


        /* =========================================================
           FEATURE SCROLLBAR
        ========================================================== */

        .features-grid.scrollable::-webkit-scrollbar {

            width: 3px;

        }


        .features-grid.scrollable::-webkit-scrollbar-thumb {

            background:
                rgba(
                    34,
                    211,
                    238,
                    0.6
                );

            border-radius: 999px;

        }


        /* =========================================================
           FEATURE PILL
        ========================================================== */

        .feature-pill {

            height: 24px;

            font-size: 11px;

            border-radius: 999px;

            background:
                rgba(
                    0,
                    0,
                    0,
                    0.4
                );

            border:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    0.25
                );

            display: flex;

            align-items: center;

            justify-content: center;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;

            padding-left: 6px;

            padding-right: 6px;

            transition:
                box-shadow
                0.2s ease,
                transform
                0.2s ease;

        }


        /* =========================================================
           FEATURE HOVER
        ========================================================== */

        @media (hover: hover) {

            .feature-pill:hover {

                box-shadow:
                    0 0 10px
                    rgba(
                        34,
                        211,
                        238,
                        0.6
                    );

                transform:
                    scale(1.05);

            }

        }


        /* =========================================================
           COUNTDOWN
        ========================================================== */

        .countdown-overlay {

            position: absolute;

            bottom: 10px;

            left: 50%;

            transform:
                translateX(-50%);

            padding:
                4px 10px;

            font-size: 11px;

            font-weight: 700;

            border-radius: 999px;

            background:
                rgba(
                    0,
                    0,
                    0,
                    0.65
                );

            backdrop-filter:
                blur(6px);

            color:
                #22d3ee;

            border:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    0.25
                );

            box-shadow:
                0 0 12px
                rgba(
                    34,
                    211,
                    238,
                    0.4
                );

            white-space: nowrap;

            pointer-events: none;

            z-index: 5;

        }


        /* =========================================================
           DESKTOP COUNTDOWN
        ========================================================== */

        @media (min-width: 640px) {

            .countdown-overlay {

                font-size: 12px;

                padding:
                    5px 12px;

            }

        }


        /* =========================================================
           MOBILE PRODUCT NAME
        ========================================================== */

        @media (max-width: 639px) {

            .product-name {

                font-size: 14px;

                line-height: 20px;

            }

        }

    </style>

</section>
