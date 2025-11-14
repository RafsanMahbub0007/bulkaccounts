<section class="relative py-32 px-6 bg-gradient-to-br from-gray-950 via-gray-900 to-black text-white overflow-hidden">
  <!-- Glowing background accents -->
  <div class="absolute inset-0">
    <div class="absolute -top-40 -left-32 w-96 h-96 bg-gradient-to-tr from-pink-500/30 via-purple-500/20 to-transparent rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-gradient-to-tl from-cyan-400/30 via-blue-500/20 to-transparent rounded-full blur-3xl"></div>
  </div>

  <div class="container mx-auto relative z-10">
    <!-- Section Header -->
    <header class="text-center mb-20 max-w-3xl mx-auto">
      <h2 class="text-6xl md:text-7xl font-extrabold bg-gradient-to-r from-pink-400 via-purple-400 to-cyan-400 bg-clip-text text-transparent drop-shadow-[0_0_25px_rgba(255,255,255,0.3)]">
        Featured Products
      </h2>
      <p class="text-xl md:text-2xl text-gray-300 mt-6 leading-relaxed">
        Handpicked selections curated to elevate your digital presence.
        <span class="text-cyan-400 font-semibold">Discover. Engage. Grow.</span>
      </p>
    </header>

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
            <img src="{{ asset('storage/' . $product->subcategory->image) }}" alt="{{ $product->name }}"
              class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            <span
              class="absolute top-3 left-3 bg-gradient-to-r from-pink-500 to-fuchsia-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
              Featured
            </span>
          </div>

          <!-- Content -->
          <div class="p-6 flex flex-col justify-between gap-4 text-gray-200">
            <div>
              <h3 class="text-2xl font-bold mb-2 bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                {{ $product->name }}
              </h3>
              <span class="text-xl font-bold text-pink-400">${{ number_format($product->selling_price ?? 49.99, 2) }}</span>
              <p class="text-gray-400 text-sm leading-relaxed mt-2">
                {{ \Illuminate\Support\Str::words($product->description, 10, '...') }}
              </p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 mt-4">
              <a href="#"
                class="flex-1 text-center px-4 py-2 text-sm font-semibold rounded-xl bg-gradient-to-r from-pink-500 to-purple-500 hover:from-pink-400 hover:to-purple-400 text-white shadow-lg shadow-pink-500/30 transition duration-300">
                Add to Cart
              </a>
              <a href="#"
                class="flex-1 text-center px-4 py-2 text-sm font-semibold rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white shadow-lg shadow-cyan-500/30 transition duration-300">
                Buy Now
              </a>
            </div>

            <!-- Tags -->
            <div class="flex flex-wrap gap-2 mt-3">
              <span class="text-xs bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-3 py-1 rounded-full shadow-md">
                New
              </span>
              <span class="text-xs bg-gradient-to-r from-green-400 to-teal-400 text-white px-3 py-1 rounded-full shadow-md">
                Trending
              </span>
            </div>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>

<style>
  /* Floating blob animation */
  @keyframes blob {
    0%, 100% { transform: translate3d(0,0,0) scale(1); }
    33% { transform: translate3d(20px,-15px,0) scale(1.1); }
    66% { transform: translate3d(-20px,15px,0) scale(0.95); }
  }
  .animate-blob { animation: blob 8s infinite ease-in-out; }
  .animation-delay-2000 { animation-delay: 2s; }
</style>
