@php
    $system = \App\Models\Setting::find(1);
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-gray-950/90 backdrop-blur-md border-b border-white/10 text-white shadow-sm">
    <div class="container mx-auto px-4 lg:px-16 flex flex-wrap items-center justify-between py-3">

        <!-- Left: Logo -->
        <a href="{{ route('home') }}" class="flex items-center mb-2 md:mb-0">
            <img src="{{image_path($system->logo) }}" alt="Logo" class="h-16 w-auto mr-2">
        </a>

        <!-- Hamburger Mobile -->
        <button @click="open = !open" class="sm:hidden ml-auto p-2 text-gray-300 hover:text-white transition">
            <i :class="open ? 'fas fa-times' : 'fas fa-bars'" class="text-xl"></i>
        </button>

        <!-- Desktop & Tablet Menu -->
        <div class="hidden sm:flex flex-1 justify-center items-center space-x-6 text-gray-300 font-medium">
            <x-nav-link href="{{ route('home') }}" class="hover:text-white transition">Home</x-nav-link>
            @livewire('category-menu')
            <x-nav-link href="{{ route('pricing') }}" class="hover:text-white transition">Pricing</x-nav-link>
            <x-nav-link href="{{ route('guidlines') }}" class="hover:text-white transition">Guideline</x-nav-link>
            <x-nav-link href="{{ route('about') }}" class="hover:text-white transition">About</x-nav-link>
            <x-nav-link href="{{ route('contact') }}" class="hover:text-white transition">Contact</x-nav-link>
        </div>

        <!-- Right: User & Cart -->
        <div class="hidden sm:flex items-center space-x-3 md:space-x-5 ml-auto">
            @guest
                <a href="{{ route('login') }}" class="px-4 py-2 border border-red-500 rounded-full text-red-500 hover:bg-red-500 hover:text-white transition duration-300">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-red-500 text-white hover:bg-red-600 transition duration-300">Register</a>
            @endguest

            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center rounded-full border border-white/20 p-1 hover:border-red-400 transition">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img class="h-10 w-10 rounded-full object-cover"
                                     src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            @else
                                <div class="h-10 w-10 bg-red-600 flex items-center justify-center rounded-full text-white">
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

            @livewire('cart-sidebar')
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="sm:hidden bg-gray-900/95 border-t border-white/10">
        <div class="px-4 py-4 space-y-4 text-gray-300">

            <x-responsive-nav-link href="{{ route('home') }}">Home</x-responsive-nav-link>
            @livewire('category-menu')
            <x-responsive-nav-link href="{{ route('pricing') }}">Pricing</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('guidlines') }}">Guideline</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('about') }}">About</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('contact') }}">Contact</x-responsive-nav-link>

            @guest
                <div class="pt-4 space-y-2">
                    <a href="{{ route('login') }}" class="block text-center px-4 py-2 border border-red-500 rounded-full text-red-500 hover:bg-red-500 hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="block text-center px-4 py-2 bg-red-500 text-white rounded-full hover:bg-red-600">Register</a>
                </div>
            @endguest

        </div>
    </div>
</nav>
