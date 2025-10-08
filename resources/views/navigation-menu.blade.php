<nav x-data="{ open: false }" class="sticky top-0 z-30 bg-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-lg font-bold">
                        <span
                            class="bg-gradient-to-r from-red-500 to-rose-500 text-transparent bg-clip-text text-4xl font-poppins">Bulk</span><br>
                        <span class="text-white text-xl font-poppins">Account Seller</span>
                    </a>
                </div>

            </div>

            <div class="hidden sm:flex space-x-4">
                <!-- Home -->
                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-nav-link>

                <!-- About -->
                <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
                    {{ __('About') }}
                </x-nav-link>

                <!-- Pricing -->
                <x-nav-link href="{{ route('pricing') }}" :active="request()->routeIs('pricing')">
                    {{ __('Pricing') }}
                </x-nav-link>

                <!-- FAQ -->
                <x-nav-link href="{{ route('faq') }}" :active="request()->routeIs('faq')">
                    {{ __('FAQ') }}
                </x-nav-link>

                <!-- Blog -->
                <x-nav-link href="{{ route('guidlines') }}" :active="request()->routeIs('guidlines')">
                    {{ __('Guideline') }}
                </x-nav-link>
                <!-- Blog -->
                <x-nav-link href="{{ route('blog') }}" :active="request()->routeIs('blog')">
                    {{ __('Blog') }}
                </x-nav-link>

                <!-- Contact -->
                <x-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">
                    {{ __('Contact') }}
                </x-nav-link>
            </div>


            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    @guest
                    <x-nav-link href="/login">Login</x-nav-link>
                    @endguest

                    @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="flex items-center justify-center w-10 h-10 text-white bg-red-500 rounded-full hover:bg-red-400">
                                        <i class="fas fa-user text-lg"></i>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('user.orders') }}">
                                {{ __('Orders') }}
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('user.payments') }}">
                                {{ __('Payments') }}
                            </x-dropdown-link>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                    @endif
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Home -->
            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>

            <!-- About -->
            <x-responsive-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
                {{ __('About') }}
            </x-responsive-nav-link>

            <!-- Pricing -->
            <x-responsive-nav-link href="{{ route('pricing') }}" :active="request()->routeIs('pricing')">
                {{ __('Pricing') }}
            </x-responsive-nav-link>

            <!-- FAQ -->
            <x-responsive-nav-link href="{{ route('faq') }}" :active="request()->routeIs('faq')">
                {{ __('FAQ') }}
            </x-responsive-nav-link>

            <!-- Blog -->
            <x-responsive-nav-link href="{{ route('blog') }}" :active="request()->routeIs('blog')">
                {{ __('Blog') }}
            </x-responsive-nav-link>

            <!-- Contact -->
            <x-responsive-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">
                {{ __('Contact') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @guest
                <x-responsive-nav-link href="/login">Login</x-responsive-nav-link>
            @endguest

            @auth
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="{{ route('user.orders') }}" :active="request()->routeIs('user.orders')">
                    {{ __('Orders') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('user.payments') }}" :active="request()->routeIs('user.payments')">
                    {{ __('Payments') }}
                </x-responsive-nav-link>

                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @endauth
        </div>
    </div>
</nav>
