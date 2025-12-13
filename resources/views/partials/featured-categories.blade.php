<section class="relative py-32 px-6 bg-gradient-to-br from-gray-950 via-gray-900 to-black text-white overflow-hidden">
    <!-- Glowing background accents -->
    <div class="absolute inset-0">
        <div
            class="absolute -top-40 -left-32 w-96 h-96 bg-gradient-to-tr from-pink-500/30 via-purple-500/20 to-transparent rounded-full blur-3xl">
        </div>
        <div
            class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-gradient-to-tl from-cyan-400/30 via-blue-500/20 to-transparent rounded-full blur-3xl">
        </div>
    </div>

    <div class="container mx-auto relative z-10">
        <!-- Section Header -->
        <header class="text-center mb-20 max-w-3xl mx-auto">
            <h2
                class="text-6xl md:text-7xl font-extrabold bg-gradient-to-r from-pink-400 via-purple-400 to-cyan-400 bg-clip-text text-transparent drop-shadow-[0_0_25px_rgba(255,255,255,0.3)]">
                Featured Products
            </h2>
            <p class="text-xl md:text-2xl text-gray-300 mt-6 leading-relaxed">
                Handpicked selections curated to elevate your digital presence.
                <span class="text-cyan-400 font-semibold">Discover. Engage. Grow.</span>
            </p>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-12">
            @foreach ($products as $product)
                <div
                    class="group relative rounded-3xl overflow-hidden shadow-xl bg-gradient-to-br from-gray-800/60 to-gray-900/80 border border-white/10 transition-transform duration-500 hover:scale-[1.03] hover:shadow-[0_0_50px_rgba(255,255,255,0.1)]">

                    <!-- IMAGE -->
                    <a href="{{ route('product.details', $product->slug) }}">
                        <div class="relative w-full h-48 overflow-hidden rounded-t-3xl">
                            <img src="{{ image_path($product->subcategory->image) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                            {{-- Featured Badge --}}
                            @if ($product->is_featured)
                                <span
                                    class="absolute top-3 left-3 bg-gradient-to-r from-pink-500 to-fuchsia-500
                          text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                    Featured
                                </span>
                            @endif

                            {{-- Offer Badge (Only if active and within date) --}}
                            @if ($product->hasOffer())
                                <span
                                    class="absolute top-3 right-3 bg-gradient-to-br from-purple-600 to-indigo-600
                          text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                    {{ $product->discountPercent() }}% OFF
                                </span>
                            @endif
                        </div>
                    </a>

                    <!-- CONTENT -->
                    <div class="p-6 flex flex-col justify-between gap-4 text-gray-200">
                        <a href="{{ route('product.details', $product->slug) }}">
                            <h3
                                class="text-xl font-bold mb-2 bg-gradient-to-r from-cyan-400 to-blue-400
                    bg-clip-text text-transparent">
                                {{ $product->name }}
                            </h3>
                        </a>

                        <!-- PRICE SECTION -->
                        <div class="flex items-center gap-3">
                            @if ($product->activeOffer())
                                <span class="text-lg font-extrabold text-green-400">
                                    ${{ number_format($product->discountedPrice(), 2) }}
                                </span>
                                <span class="text-sm line-through text-gray-400">
                                    ${{ number_format($product->selling_price, 2) }}
                                </span>
                            @else
                                <span class="text-lg font-bold text-pink-400">
                                    ${{ number_format($product->selling_price, 2) }}
                                </span>
                            @endif
                        </div>


                        {{-- OFFER COUNTDOWN --}}
                        @if ($product->hasOffer())
                            @php
                                $end = $product->activeOffer()->end_date;
                            @endphp
                            <div class="text-[11px] text-gray-400" x-data="{ timeLeft: '' }" x-init="const end = new Date('{{ $end }}').getTime();

                            setInterval(() => {
                                const now = Date.now();
                                let diff = end - now;

                                if (diff <= 0) {
                                    timeLeft = 'Offer ended';
                                    return;
                                }

                                let days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                let seconds = Math.floor((diff % (1000 * 60)) / 1000);

                                // reverse countdown format
                                timeLeft = `${days}d : ${hours}h : ${minutes}m : ${seconds}s`;
                            }, 1000);"
                                x-text="timeLeft"></div>
                        @endif

                        <!-- FEATURES -->
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach ($product->featureList() as $feature)
                                <span
                                    class="relative text-xs px-3 py-1 rounded-full font-semibold text-white
                        bg-gray-900/30 border border-white/20 shadow-[0_0_10px_rgba(255,255,255,0.2)]">
                                    {{ $feature }}
                                </span>
                            @endforeach
                        </div>

                        <!-- CART OR PRE-ORDER -->
                        @if ($product->outOfStock())
                            <a href="{{ $system->pre_order_link ? $system->pre_order_link : '#' }}"
                                class="block text-center font-bold py-2.5 px-4 rounded-xl
          bg-gradient-to-r from-yellow-400 to-amber-500
          text-black shadow-[0_0_15px_rgba(255,200,0,0.4)]
          hover:scale-105 hover:shadow-[0_0_25px_rgba(255,200,0,0.7)]
          transition-all duration-300 ease-out">
                                Contact for Pre-Order
                            </a>
                        @else
                            @livewire('add-to-cart', ['productId' => $product->id], key('product-' . $product->id))
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

<style>
    /* Floating blob animation */
    @keyframes blob {

        0%,
        100% {
            transform: translate3d(0, 0, 0) scale(1);
        }

        33% {
            transform: translate3d(20px, -15px, 0) scale(1.1);
        }

        66% {
            transform: translate3d(-20px, 15px, 0) scale(0.95);
        }
    }

    .animate-blob {
        animation: blob 8s infinite ease-in-out;
    }

    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
