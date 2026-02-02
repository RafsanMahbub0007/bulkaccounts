<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $system = cache()->remember('system_settings', 3600, fn() => \App\Models\Setting::find(1));
        $banners = cache()->remember('banners_list', 3600, fn() => \App\Models\Banner::all());
        $offer = cache()->remember('active_offer', 600, fn() => \App\Models\Offer::where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->first());
    @endphp

    <title>{{ $system->website_name ?? 'Jabed' }}</title>
    <!-- FavIcon -->
    <link rel="shortcut icon" href="{{ image_path($system->favicon) }}" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Fontawsome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('partials.topbar')
        @include('partials.offer')
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        @include('partials.footer')
    </div>

    @stack('modals')

    @livewireScripts
    <!-- Floating Support Icons -->
    <div
        class="fixed
            bottom-4 right-4
            sm:bottom-6 sm:right-6
            lg:bottom-8 lg:right-8
            flex flex-col gap-3
            z-50">
        <!-- WhatsApp -->
        <a href="{{ $system->sup_wa_link ? $system->sup_wa_link : '#' }}" target="_blank" aria-label="WhatsApp Support"
            class="flex items-center justify-center
              w-11 h-11
              sm:w-12 sm:h-12
              lg:w-14 lg:h-14
              rounded-full
              bg-green-500 text-white
              shadow-lg
              hover:bg-green-600
              hover:scale-110
              active:scale-95
              transition">
            <i class="fa-brands fa-whatsapp text-xl sm:text-2xl lg:text-3xl"></i>
        </a>
        <!-- Telegram -->
        <a href="{{ $system->sup_tele_link ? $system->sup_tele_link : '#' }}" target="_blank"
            aria-label="Telegram Support"
            class="flex items-center justify-center
              w-11 h-11
              sm:w-12 sm:h-12
              lg:w-14 lg:h-14
              rounded-full
              bg-blue-500 text-white
              shadow-lg
              hover:bg-blue-600
              hover:scale-110
              active:scale-95
              transition">
            <i class="fa-brands fa-telegram text-xl sm:text-2xl lg:text-3xl"></i>
        </a>
    </div>
</body>

</html>
