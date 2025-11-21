
@if ($offer == true)
<section class="relative overflow-hidden text-white py-4">
  <!-- Background Gradient -->
  <div class="absolute inset-0 bg-gradient-to-r from-pink-600 via-purple-600 to-cyan-500 animate-[gradientMove_10s_linear_infinite] opacity-90"></div>

  <!-- Soft Light Orbs -->
  <div class="absolute -top-20 left-10 w-64 h-64 bg-pink-400/30 rounded-full blur-3xl"></div>
  <div class="absolute top-10 right-0 w-96 h-96 bg-cyan-400/30 rounded-full blur-3xl"></div>

  <!-- Overlay -->
  <div class="absolute inset-0 bg-black/40 backdrop-blur-md"></div>

  <!-- Content -->
  <div class="relative z-10 container mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-5">

    <!-- Offer Text -->
    <div class="flex items-center justify-center sm:justify-start gap-3 overflow-hidden w-full sm:flex-1">
      <div class="animate-[slide_14s_linear_infinite] whitespace-nowrap">
        <span class="text-yellow-300 text-2xl drop-shadow-[0_0_10px_rgba(255,255,255,0.7)]">⚡</span>
        <span class="bg-gradient-to-r from-yellow-200 via-white to-yellow-200 bg-clip-text text-transparent font-extrabold text-lg md:text-xl tracking-wide shimmer">
          {{ $offer->title ?? '⚡ Flash Deal: 20% OFF all accounts! Use code: PVAPRO20 ⚡' }}!! {{ $offer->description ?? '' }}
        </span>
        <span class="text-yellow-300 text-2xl drop-shadow-[0_0_10px_rgba(255,255,255,0.7)]">⚡</span>
      </div>
    </div>

    <!-- Buttons -->
    <div class="flex flex-col sm:flex-row items-center gap-3">
      <a href="{{ route('pricing') }}"
        class="px-6 py-2 rounded-full text-sm md:text-base font-bold text-white bg-gradient-to-r from-pink-500 via-purple-500 to-cyan-500 hover:from-pink-400 hover:to-cyan-400 shadow-lg shadow-pink-500/30 transition-all duration-300">
        Shop Now →
      </a>
      <a href="{{ route('register') }}"
        class="px-6 py-2 rounded-full text-sm md:text-base font-bold text-white bg-gradient-to-r from-cyan-400 via-blue-500 to-violet-500 hover:from-cyan-300 hover:to-violet-400 shadow-lg shadow-cyan-500/30 transition-all duration-300">
        Become a Seller
      </a>
    </div>
  </div>

  <!-- Floating Particles -->
  <span class="absolute w-3 h-3 bg-white/60 rounded-full blur-sm animate-[float_6s_ease-in-out_infinite]" style="left:20%; top:80%; animation-delay:0s;"></span>
  <span class="absolute w-2 h-2 bg-white/60 rounded-full blur-sm animate-[float_5s_ease-in-out_infinite]" style="left:50%; top:75%; animation-delay:1s;"></span>
  <span class="absolute w-3 h-3 bg-white/60 rounded-full blur-sm animate-[float_7s_ease-in-out_infinite]" style="left:80%; top:70%; animation-delay:0.5s;"></span>

  <!-- Animations -->
  <style>
    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    @keyframes slide {
      0% { transform: translateX(100%); }
      100% { transform: translateX(-100%); }
    }

    @keyframes shimmer {
      0% { background-position: 200%; }
      100% { background-position: -200%; }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); opacity: 0.8; }
      50% { transform: translateY(-25px); opacity: 1; }
    }

    .shimmer {
      animation: shimmer 2s linear infinite;
      background-size: 150%;
    }
  </style>
</section>
@endif
