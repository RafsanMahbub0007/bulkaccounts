<div class="w-full bg-gray-950/80 backdrop-blur-md border-b border-white/10 text-white sticky top-0 z-50">
    <div class="container mx-auto px-6 lg:px-16 py-3 flex flex-col sm:flex-row justify-between items-center">

        <!-- ✅ Left: Contact Info -->
        <div class="flex flex-col sm:flex-row items-center sm:space-x-10 mb-3 sm:mb-0">

            <div class="flex items-center space-x-3 group cursor-pointer">
                <i class="fas fa-phone-alt text-red-500 text-lg transition group-hover:text-red-400"></i>
                <a href="tel:+1234567890"
                   class="text-sm font-medium text-gray-300 group-hover:text-white transition">
                    +1 234 567 890
                </a>
            </div>

            <div class="flex items-center space-x-3 group cursor-pointer mt-2 sm:mt-0">
                <i class="fas fa-envelope text-red-500 text-lg transition group-hover:text-red-400"></i>
                <a href="mailto:support@bulkaccounts.com"
                   class="text-sm font-medium text-gray-300 group-hover:text-white transition">
                    support@bulkaccounts.com
                </a>
            </div>

        </div>


        <!-- ✅ Right: Social Icons -->
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

        <div class="flex items-center space-x-3">
            @foreach ($socialLinks as $platform)
                @if (!empty($platform['url']))
                    <a href="{{ $platform['url'] }}" target="_blank"
                       class="w-9 h-9 flex items-center justify-center rounded-full
                              border border-red-500 text-red-500
                              hover:bg-red-600 hover:border-red-600 hover:text-white
                              shadow-[0_0_10px_rgba(255,0,0,0.4)]
                              hover:shadow-[0_0_15px_rgba(255,0,0,0.8)]
                              transition-all duration-300">
                        <i class="fab fa-{{ $platform['icon'] }} text-sm"></i>
                    </a>
                @endif
            @endforeach
        </div>

    </div>
</div>
