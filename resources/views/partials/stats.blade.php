<div class="container mx-auto mt-16 sm:mt-20 lg:mt-28 relative z-10 mb-20">
    <div class="text-center mb-8 sm:mb-10 lg:mb-12">
        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold tracking-tight">
            <span class="bg-gradient-to-r from-blue-400 via-indigo-300 to-amber-300 bg-clip-text text-transparent">
                Our Divine Achievements
            </span>
        </h2>
        <p class="text-gray-400 mt-2 sm:mt-3 lg:mt-4 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto leading-relaxed px-4">
            A radiant path of trust, quality, and excellence — illuminated by our valued clients worldwide.
        </p>
    </div>

    <!-- Stats Grid - Responsive -->
    <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 xl:gap-10">
        @foreach ([['icon' => 'fas fa-user-check', 'count' => '88500', 'label' => 'Accounts Verified', 'gradient' => 'from-blue-500 via-indigo-400 to-cyan-400', 'hover' => 'from-blue-400 via-indigo-400 to-cyan-300'], ['icon' => 'fas fa-calendar-alt', 'count' => '11', 'label' => 'Years of Experience', 'gradient' => 'from-indigo-400 via-purple-500 to-blue-500', 'hover' => 'from-purple-400 via-indigo-400 to-blue-500'], ['icon' => 'fas fa-smile', 'count' => '99', 'label' => 'Client Satisfaction', 'gradient' => 'from-amber-400 via-pink-400 to-purple-500', 'hover' => 'from-amber-400 via-pink-400 to-purple-500'], ['icon' => 'fas fa-headset', 'count' => '24', 'label' => '24/7 Support', 'gradient' => 'from-blue-300 via-amber-400 to-pink-400', 'hover' => 'from-blue-300 via-amber-400 to-pink-400']] as $stat)
            <div
                class="stat-card group relative bg-white/5 backdrop-blur-lg sm:backdrop-blur-xl border border-white/10 rounded-xl sm:rounded-2xl lg:rounded-3xl p-4 sm:p-6 lg:p-8 xl:p-10 text-center shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 sm:hover:-translate-y-2 duration-300 overflow-hidden">
                <div
                    class="absolute inset-0 opacity-0 group-hover:opacity-10 bg-gradient-to-br {{ $stat['hover'] }} blur-xl transition-all duration-500">
                </div>
                <div
                    class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 lg:w-20 lg:h-20 mb-3 sm:mb-4 lg:mb-6 rounded-full bg-gradient-to-br {{ $stat['gradient'] }} text-white shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all duration-300">
                    <i class="{{ $stat['icon'] }} text-lg sm:text-xl lg:text-2xl xl:text-3xl"></i>
                </div>
                <h3 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white counter"
                    data-count="{{ $stat['count'] }}">0</h3>
                <p class="text-gray-400 mt-1 sm:mt-2 lg:mt-3 text-xs sm:text-sm lg:text-base xl:text-lg">
                    {{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</div>