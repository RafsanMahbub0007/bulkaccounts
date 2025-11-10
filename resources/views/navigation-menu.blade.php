<nav x-data="{ open: false, catOpen: false }" class="sticky top-0 z-50 bg-gray-900/90 backdrop-blur-md shadow border-b border-white/10">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- Left: Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex flex-col leading-tight">
                    <span class="bg-gradient-to-r from-red-500 to-rose-500 text-transparent bg-clip-text text-3xl font-bold">Pva</span>
                    <span class="text-white text-2xl font-semibold tracking-wide">ProSeller</span>
                </a>
            </div>

            <!-- Center: Main Links -->
            <div class="hidden lg:flex flex-1 justify-center">
                <ul class="flex items-center space-x-8 text-gray-300 font-medium">
                    <x-nav-link href="{{ route('home') }}">Home</x-nav-link>

                    <!-- Categories Dropdown -->
                    @livewire('category-menu')

                    <x-nav-link href="{{ route('pricing') }}">Pricing</x-nav-link>
                    <x-nav-link href="{{ route('guidlines') }}">Guideline</x-nav-link>
                    <x-nav-link href="{{ route('about') }}">About</x-nav-link>
                    <x-nav-link href="{{ route('contact') }}">Contact</x-nav-link>
                </ul>
            </div>

            <!-- Right: Login/Register or User -->
            <div class="hidden lg:flex items-center space-x-2 text-sm font-medium">
                @guest
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded-full border border-red-500 text-red-500 hover:bg-red-500 hover:text-white transition duration-300 ease-in-out shadow-sm">
                       Login
                    </a>
                    <span class="text-gray-400 select-none">|</span>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-full bg-red-500 text-white hover:bg-red-600 transition duration-300 ease-in-out shadow-sm">
                       Register
                    </a>
                @endguest

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center rounded-full border border-white/20 p-1 hover:border-red-400 transition">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <img class="size-9 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
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

            <!-- Hamburger Menu -->
            <button @click="open = !open" class="lg:hidden p-2 text-gray-300 hover:text-white transition">
                <i :class="open ? 'fas fa-times' : 'fas fa-bars'" class="text-xl"></i>
            </button>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="lg:hidden bg-gray-800 border-t border-white/10">
        <div class="px-4 pt-4 pb-6 space-y-3 text-gray-300">
            <x-responsive-nav-link href="{{ route('home') }}">Home</x-responsive-nav-link>

            <!-- Mobile Categories -->
           <!-- Categories Dropdown -->
                    @livewire('category-menu')

            <x-responsive-nav-link href="{{ route('pricing') }}">Pricing</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('guidlines') }}">Guideline</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('about') }}">About</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('contact') }}">Contact</x-responsive-nav-link>

            @guest
                <div class="pt-3 space-y-2">
                    <a href="{{ route('login') }}"
                        class="block w-full text-center px-4 py-2 rounded-full border border-red-500 text-red-500 font-semibold hover:bg-red-500 hover:text-white transition">Login</a>
                    <a href="{{ route('register') }}"
                        class="block w-full text-center px-4 py-2 rounded-full bg-red-500 text-white font-semibold hover:bg-red-600 transition">Register</a>
                </div>
            @endguest
        </div>
    </div>
</nav>
