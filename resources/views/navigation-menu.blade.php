<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-gray-950/80 backdrop-blur-md border-b border-white/10 text-white shadow-sm">

    <div class="container mx-auto px-6 lg:px-16 py-3 flex flex-col sm:flex-row justify-between items-center">

        <!-- Left: Logo -->
        <div class="flex items-center space-x-4 mb-3 sm:mb-0">
            <a href="{{ route('dashboard') }}" class="flex flex-col leading-tight">
                <span class="bg-gradient-to-r from-red-500 to-rose-500 text-transparent bg-clip-text text-3xl font-bold">Pva</span>
                <span class="text-white text-2xl font-semibold tracking-wide">ProSeller</span>
            </a>
        </div>

        <!-- Center: Menu Links -->
        <ul class="hidden sm:flex flex-1 justify-center items-center space-x-6 text-gray-300 font-medium">
            <x-nav-link href="{{ route('home') }}" class="hover:text-white transition">Home</x-nav-link>
            @livewire('category-menu')
            <x-nav-link href="{{ route('pricing') }}" class="hover:text-white transition">Pricing</x-nav-link>
            <x-nav-link href="{{ route('guidlines') }}" class="hover:text-white transition">Guideline</x-nav-link>
            <x-nav-link href="{{ route('about') }}" class="hover:text-white transition">About</x-nav-link>
            <x-nav-link href="{{ route('contact') }}" class="hover:text-white transition">Contact</x-nav-link>
        </ul>

        <!-- Right: Auth + Social -->
        <div class="flex items-center space-x-4">
            <!-- Social Links -->
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

            @foreach ($socialLinks as $platform)
                @if (!empty($platform['url']))
                    <a href="{{ $platform['url'] }}" target="_blank"
                       class="w-9 h-9 flex items-center justify-center rounded-full border border-red-500 text-red-500
                              hover:bg-red-600 hover:border-red-600 hover:text-white
                              shadow-[0_0_10px_rgba(255,0,0,0.4)]
                              hover:shadow-[0_0_15px_rgba(255,0,0,0.8)]
                              transition-all duration-300">
                        <i class="fab fa-{{ $platform['icon'] }} text-sm"></i>
                    </a>
                @endif
            @endforeach

            <!-- Auth Buttons -->
            @guest
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-full border border-red-500 text-red-500 hover:bg-red-500 hover:text-white transition duration-300">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-red-500 text-white hover:bg-red-600 transition duration-300">Register</a>
            @endguest

            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center rounded-full border border-white/20 p-1 hover:border-red-400 transition">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img class="size-9 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            @else
                                <div class="w-10 h-10 bg-red-600 flex items-center justify-center rounded-full text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link href="{{ route('user.orders') }}">Orders</x-dropdown-link>
                        <x-dropdown-link href="{{ route('user.payments') }}">Payments</x-dropdown-link>
                        <x-dropdown-link href="{{ route('profile.show') }}">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">Log Out</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @endauth
        </div>

        <!-- Mobile Hamburger -->
        <button @click="open = !open" class="sm:hidden p-2 text-gray-300 hover:text-white transition ml-2">
            <i :class="open ? 'fas fa-times' : 'fas fa-bars'" class="text-xl"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="sm:hidden bg-gray-800 border-t border-white/10">
        <div class="px-4 pt-4 pb-6 space-y-3 text-gray-300">
            <x-responsive-nav-link href="{{ route('home') }}">Home</x-responsive-nav-link>
            @livewire('category-menu')
            <x-responsive-nav-link href="{{ route('pricing') }}">Pricing</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('guidlines') }}">Guideline</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('about') }}">About</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('contact') }}">Contact</x-responsive-nav-link>

            @guest
                <div class="pt-3 space-y-2">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 rounded-full border border-red-500 text-red-500 font-semibold hover:bg-red-500 hover:text-white transition">Login</a>
                    <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 rounded-full bg-red-500 text-white font-semibold hover:bg-red-600 transition">Register</a>
                </div>
            @endguest
        </div>
    </div>
</nav>
