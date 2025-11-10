@if ($system->offer_status ?? true)
<div class="w-full relative overflow-hidden text-white shadow-xl">

    <!-- Inline CSS for animations and effects -->
    <style>
        /* Gradient background flow */
        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animated-bg {
            background: linear-gradient(90deg, #ff3d5a, #ff6f91, #ff9671, #ff3d5a);
            background-size: 300% 300%;
            animation: gradientFlow 8s ease infinite;
        }

        /* Smooth fade-in */
        @keyframes fadeInSmooth {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Floating particles */
        @keyframes floatUp {
            0% { transform: translateY(0) scale(0.7); opacity: 0.8; }
            100% { transform: translateY(-50px) scale(1.2); opacity: 0; }
        }
        .particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: rgba(255,255,255,0.6);
            border-radius: 50%;
            filter: blur(4px);
            animation: floatUp 4.5s linear infinite;
        }

        /* Marquee */
        @keyframes slideText {
            0% { transform: translateX(120%); }
            100% { transform: translateX(-120%); }
        }
        .marquee {
            white-space: nowrap;
            display: inline-block;
            animation: slideText 12s linear infinite;
        }

        /* Shimmer effect */
        .shimmer {
            background: linear-gradient(90deg, rgba(255,255,255,0.15), rgba(255,255,255,0.9), rgba(255,255,255,0.15));
            background-size: 180%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmerMove 2s linear infinite;
        }
        @keyframes shimmerMove { 0% { background-position: 180%; } 100% { background-position: -180%; } }

        /* Neon pulse button */
        @keyframes neonPulse {
            0% { box-shadow: 0 0 8px rgba(255,255,255,0.6); }
            50% { box-shadow: 0 0 20px rgba(255,255,255,1); }
            100% { box-shadow: 0 0 8px rgba(255,255,255,0.6); }
        }
        .neon-btn {
            animation: neonPulse 1.8s ease-in-out infinite;
        }
    </style>

    <!-- Animated gradient background -->
    <div class="animated-bg w-full h-full absolute inset-0 opacity-95"></div>

    <!-- Floating particles -->
    <span class="particle" style="left:12%; top:60%; animation-delay:0s;"></span>
    <span class="particle" style="left:32%; top:70%; animation-delay:0.8s;"></span>
    <span class="particle" style="left:55%; top:65%; animation-delay:1.4s;"></span>
    <span class="particle" style="left:78%; top:55%; animation-delay:0.4s;"></span>

    <!-- Content -->
    <div class="relative backdrop-blur-md bg-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 animate-[fadeInSmooth_0.7s_ease-out] flex flex-col sm:flex-row items-center justify-between gap-4">

            <!-- Marquee / Offer Text -->
            <div class="w-full sm:flex-1 overflow-hidden">
                <div class="marquee flex items-center gap-3 text-lg font-semibold">
                    <span class="text-yellow-300 text-2xl drop-shadow-xl">🔥</span>
                    <span class="shimmer tracking-wide">
                        {{ $system->offer_text ?? 'Get 20% OFF all accounts! Use code: PVAPRO20' }}
                    </span>
                    <span class="text-yellow-300 text-2xl drop-shadow-xl">🔥</span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <!-- Shop Now -->
                <a href="{{ route('pricing') }}"
                   class="neon-btn bg-white text-red-600 font-bold px-6 py-2 rounded-full shadow-xl hover:bg-gray-100 transition-all duration-300">
                    Shop Now →
                </a>

                
                <!-- Become a Seller -->
                 <x-link-button href="#">
                                {{ __('Become a Sellar') }}
                            </x-link-button>

            </div>
        </div>
    </div>
</div>
@endif
