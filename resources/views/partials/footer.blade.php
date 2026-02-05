<footer class="relative overflow-hidden bg-gray-950 text-gray-300 pt-16 md:pt-24 pb-12 px-4 sm:px-6 lg:px-12">

    <!-- BACKGROUND GLOW LAYERS -->
    <div class="absolute inset-0 pointer-events-none">
        <div
            class="absolute -top-32 left-1/2 -translate-x-1/2 w-[400px] sm:w-[500px] md:w-[600px] lg:w-[700px] h-[400px] sm:h-[500px] md:h-[600px] lg:h-[700px] bg-red-500/20 blur-[100px] sm:blur-[120px] md:blur-[140px] rounded-full">
        </div>
        <div
            class="absolute bottom-10 right-5 sm:right-10 md:right-20 w-[300px] sm:w-[400px] md:w-[500px] h-[300px] sm:h-[400px] md:h-[500px] bg-purple-600/20 blur-[80px] sm:blur-[100px] md:blur-[120px] rounded-full">
        </div>
        <div
            class="absolute top-40 left-5 sm:left-10 md:left-20 w-[250px] sm:w-[300px] md:w-[350px] h-[250px] sm:h-[300px] md:h-[350px] bg-blue-600/20 blur-[80px] sm:blur-[100px] md:blur-[120px] rounded-full">
        </div>
    </div>

    <div
        class="relative container mx-auto max-w-7xl flex flex-col lg:flex-row justify-between items-start gap-10 lg:gap-12">

        <!-- LOGO & ABOUT -->
        <div class="flex-1 min-w-[200px]">
            <div class="flex items-center gap-4 mb-4">
                <img src="{{ image_path($system->logo) }}" alt="Logo"
                    class="w-24 sm:w-28 md:w-32 h-auto rounded-xl shadow-lg liquid">
            </div>
            <p class="text-gray-400 text-sm sm:text-base leading-relaxed">
                Trusted provider of high-quality bulk social media accounts, delivering speed, reliability, and global
                trust.
            </p>
        </div>

        <!-- QUICK LINKS -->
        <div class="flex-1 min-w-[180px]">
            <h3
                class="text-lg sm:text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400 mb-4">
                Quick Links
            </h3>
            <ul class="space-y-2 text-gray-300 text-sm sm:text-base">
                <li><a href="{{ route('blog') }}"
                        class="hover:text-red-400 hover:underline underline-offset-4 transition">Blog</a></li>
                <li><a href="{{ route('about') }}"
                        class="hover:text-red-400 hover:underline underline-offset-4 transition">About</a></li>
                <li><a href="{{ route('contact') }}"
                        class="hover:text-red-400 hover:underline underline-offset-4 transition">Contact</a></li>
                <li><a href="{{ route('faq') }}"
                        class="hover:text-red-400 hover:underline underline-offset-4 transition">FAQ</a></li>
                <li><a href="{{ route('privacy') }}"
                        class="hover:text-red-400 hover:underline underline-offset-4 transition">Privacy Policy</a></li>
                <li><a href="{{ route('terms') }}"
                        class="hover:text-red-400 hover:underline underline-offset-4 transition">Terms & Conditions</a>
                </li>
            </ul>
        </div>

        <!-- CATEGORIES -->
        @php
            $cats = cache()->remember('footer_categories', 3600, fn() => \App\Models\Category::where('is_active', 1)->orderBy('order', 'ASC')->get());
        @endphp
        <div class="flex-1 min-w-[180px]">
            <h3
                class="text-lg sm:text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-teal-400 mb-4">
                Categories
            </h3>
            <ul class="space-y-2 text-gray-300 text-sm sm:text-base">
                @forelse ($cats as $cat)
                    <li><a href="{{ route('category.details', $cat->slug) }}"
                            class="hover:text-red-400 hover:underline underline-offset-4 transition">{{ $cat->name }}</a>
                    </li>
                @empty
                    <li class="text-gray-500">No Categories Yet</li>
                @endforelse
            </ul>
        </div>

        <!-- CONTACT -->
        <div class="flex-1 min-w-[200px]">
            <h3
                class="text-lg sm:text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-blue-500 mb-4">
                Contact Us
            </h3>
            <ul class="space-y-4 text-sm sm:text-base">
                <li class="flex items-center gap-3">
                    <i class="fas fa-envelope text-red-400"></i>
                    <a href="mailto:{{ $system->email }}"
                        class="hover:text-red-400 transition">{{ $system->email }}</a>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fas fa-phone text-blue-400"></i>
                    <a href="tel:{{ $system->phone }}" class="hover:text-red-400 transition">{{ $system->phone }}</a>
                </li>
                <li class="flex items-center gap-3">
                    <i class="fas fa-map-marker-alt text-green-400"></i>
                    <span class="text-gray-400">{{ $system->address }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- SOCIAL ICONS -->
    @php
        $socialLinks = [
            'facebook' => ['url' => $system->f_link ?? null, 'icon' => 'facebook-f'],
            'telegram' => ['url' => $system->t_link ?? null, 'icon' => 'telegram'],
            'twitter' => ['url' => $system->tw_link ?? null, 'icon' => 'twitter'],
            'instagram' => ['url' => $system->i_link ?? null, 'icon' => 'instagram'],
            'linkedin' => ['url' => $system->lnkd_link ?? null, 'icon' => 'linkedin-in'],
            'youtube' => ['url' => $system->y_link ?? null, 'icon' => 'youtube'],
        ];
    @endphp

    <div class="mt-10 sm:mt-12 text-center hidden sm:block">
        <h3
            class="text-lg sm:text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mb-4">
            Follow Us
        </h3>
        <div class="flex justify-center gap-4 sm:gap-6 flex-wrap">
            @foreach ($socialLinks as $platform)
                @if (!empty($platform['url']))
                    <a href="{{ $platform['url'] }}" target="_blank"
                        class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full
                          border border-red-500 text-red-500
                          hover:bg-gradient-to-r hover:from-pink-500 hover:via-purple-500 hover:to-cyan-500
                          hover:text-white
                          shadow-[0_0_10px_rgba(255,0,0,0.4)]
                          hover:shadow-[0_0_15px_rgba(255,255,255,0.6)]
                          transition-all duration-300 animate-bounce-slow">
                        <i class="fab fa-{{ $platform['icon'] }} text-lg sm:text-xl"></i>
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    <!-- FOOTER BOTTOM -->
    <div
        class="mt-10 sm:mt-12 border-t border-white/10 pt-6 text-center text-sm sm:text-base text-gray-500 flex flex-col sm:flex-row justify-center items-center gap-2 sm:gap-3">
        <span>&copy; {{ date('Y') }} Pva Pro-Seller. All rights reserved.</span>
        <span class="hidden sm:inline">|</span>
        <a href="{{ route('privacy') }}" class="hover:text-red-400 transition-colors">Privacy Policy</a>
    </div>

</footer>
