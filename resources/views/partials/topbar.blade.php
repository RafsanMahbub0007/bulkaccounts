<div class="w-full sticky top-0 z-50">
    <!-- Gradient background with blur and subtle animation -->
    <div class="relative bg-gray-950/80 backdrop-blur-md border-b border-white/10 text-white overflow-hidden">
        <!-- Gradient overlay animation -->
        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-cyan-500 opacity-20 animate-[gradientMove_8s_linear_infinite] pointer-events-none"></div>

        <div class="container mx-auto px-6 lg:px-16 py-3 flex flex-col sm:flex-row justify-between items-center relative z-10">

            <!-- Contact Info -->
            <div class="flex flex-col sm:flex-row items-center sm:space-x-10 mb-3 sm:mb-0">

                <div class="flex items-center space-x-3 group cursor-pointer">
                    <i class="fas fa-phone text-red-500 text-lg transition group-hover:text-yellow-400"></i>
                    <a href="tel:{{$system->phone}}" class="text-sm font-medium text-gray-300 group-hover:text-white transition">
                        {{$system->phone}}
                    </a>
                </div>

                <div class="flex items-center space-x-3 group cursor-pointer mt-2 sm:mt-0">
                    <i class="fas fa-envelope text-red-500 text-lg transition group-hover:text-yellow-400"></i>
                    <a href="mailto:support@bulkaccounts.com" class="text-sm font-medium text-gray-300 group-hover:text-white transition">
                        {{$system->email}}
                    </a>
                </div>
            </div>

            <!-- Social Icons -->
            @php
                $socialLinks = [
                    'facebook' => ['url' => $system->f_link ?? null, 'icon' => 'facebook-f'],
                    'telegram' => ['url' => $system->t_link ?? null, 'icon' => 'telegram'],
                    'twitter' => ['url' => $system->tw_link ?? null, 'icon' => 'twitter'],
                    'instagram' => ['url' => $system->i_link?? null,'icon' => 'instagram'],
                    'linkedin' => ['url' => $system->lnkd_link ?? null, 'icon' => 'linkedin-in'],
                    'youtube' => ['url' => $system->y_link  ?? null, 'icon' => 'youtube'],
                ];
            @endphp

            <div class="flex items-center space-x-3 mt-4">
                @foreach ($socialLinks as $platform)
                    @if (!empty($platform['url']))
                        <a href="{{ $platform['url'] }}" target="_blank"
                           class="w-9 h-9 flex items-center justify-center rounded-full
                                  border border-red-500 text-red-500
                                  hover:bg-gradient-to-r hover:from-pink-500 hover:via-purple-500 hover:to-cyan-500
                                  hover:text-white
                                  shadow-[0_0_10px_rgba(255,0,0,0.4)]
                                  hover:shadow-[0_0_15px_rgba(255,255,255,0.6)]
                                  transition-all duration-300 animate-bounce-slow">
                            <i class="fab fa-{{ $platform['icon'] }} text-sm"></i>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes bounceSlow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }

    .animate-bounce-slow {
        animation: bounceSlow 3s ease-in-out infinite;
    }
</style>
