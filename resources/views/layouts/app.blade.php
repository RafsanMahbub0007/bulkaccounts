<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $system = \App\Models\Setting::find(1);
        $banners = \App\Models\Banner::all();
        $offer = \App\Models\Offer::where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->first();
        $seoTitle = trim($__env->yieldContent('title', $system->website_name ?? 'Jabed'));
        $seoDescription = trim($__env->yieldContent('description', 'Best place to buy bulk accounts and digital products.'));
        $seoKeywords = trim($__env->yieldContent('keywords', 'bulk accounts, buy accounts, digital products'));
        $seoType = trim($__env->yieldContent('og_type', 'website'));
        $seoImage = trim($__env->yieldContent('og_image', image_path($system->logo ?? 'default-logo.png')));
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="{{ $seoKeywords }}">

    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:secure_url" content="{{ $seoImage }}">
    <meta property="og:image:alt" content="{{ $seoTitle }}">
    <meta property="og:site_name" content="{{ $system->website_name ?? 'Jabed' }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    <meta name="twitter:image:alt" content="{{ $seoTitle }}">
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
    @stack('schema')
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
    <script>
        (() => {
            let pendingCartTrigger = null;

            const getCartTarget = () => document.querySelector('[data-cart-target]');

            const pulseCartTarget = () => {
                const cartTarget = getCartTarget();

                if (!cartTarget || typeof cartTarget.animate !== 'function') {
                    return;
                }

                cartTarget.animate([
                    { transform: 'translateY(-50%) scale(1)' },
                    { transform: 'translateY(-50%) scale(1.12)' },
                    { transform: 'translateY(-50%) scale(1)' },
                ], {
                    duration: 450,
                    easing: 'ease-out',
                });

                const badge = cartTarget.querySelector('[data-cart-count]');

                if (badge && typeof badge.animate === 'function') {
                    badge.animate([
                        { transform: 'scale(1)' },
                        { transform: 'scale(1.2)' },
                        { transform: 'scale(1)' },
                    ], {
                        duration: 400,
                        easing: 'ease-out',
                    });
                }
            };

            const createFlyingClone = (sourceSnapshot) => {
                const clone = sourceSnapshot.node;

                clone.style.position = 'fixed';
                clone.style.left = `${sourceSnapshot.rect.left}px`;
                clone.style.top = `${sourceSnapshot.rect.top}px`;
                clone.style.width = `${sourceSnapshot.rect.width}px`;
                clone.style.height = `${sourceSnapshot.rect.height}px`;
                clone.style.margin = '0';
                clone.style.zIndex = '9999';
                clone.style.pointerEvents = 'none';
                clone.style.transition = 'transform 700ms cubic-bezier(0.22, 1, 0.36, 1), opacity 700ms ease-out';
                clone.style.transformOrigin = 'center center';

                document.body.appendChild(clone);

                return {
                    clone,
                    sourceRect: sourceSnapshot.rect,
                };
            };

            const animateToCart = (sourceSnapshot) => {
                const cartTarget = getCartTarget();

                if (!sourceSnapshot || !cartTarget) {
                    return;
                }

                const { clone, sourceRect } = createFlyingClone(sourceSnapshot);
                const cartRect = cartTarget.getBoundingClientRect();

                const translateX = (cartRect.left + (cartRect.width / 2)) - (sourceRect.left + (sourceRect.width / 2));
                const translateY = (cartRect.top + (cartRect.height / 2)) - (sourceRect.top + (sourceRect.height / 2));

                requestAnimationFrame(() => {
                    clone.style.transform = `translate(${translateX}px, ${translateY}px) scale(0.2)`;
                    clone.style.opacity = '0.15';
                });

                window.setTimeout(() => {
                    clone.remove();
                    pulseCartTarget();
                }, 720);
            };

            document.addEventListener('click', (event) => {
                const trigger = event.target.closest('[data-add-to-cart-trigger]');

                if (!trigger) {
                    return;
                }

                pendingCartTrigger = {
                    rect: trigger.getBoundingClientRect(),
                    node: trigger.cloneNode(true),
                };
            }, true);

            window.addEventListener('cartUpdated', () => {
                if (!pendingCartTrigger) {
                    return;
                }

                const trigger = pendingCartTrigger;
                pendingCartTrigger = null;

                animateToCart(trigger);
            });

            window.addEventListener('cartUpdateFailed', () => {
                pendingCartTrigger = null;
            });
        })();
    </script>
    <script>
        (() => {
            const rootSelector = '[data-country-code-root]';
            const toggleSelector = '[data-country-code-toggle]';
            const menuSelector = '[data-country-code-menu]';
            const optionSelector = '[data-country-code-option]';
            const searchSelector = '[data-country-code-search]';
            const maxVisibleOptions = 5;

            const setMenuMaxHeight = (menu) => {
                if (!menu) {
                    return;
                }

                const search = menu.querySelector(searchSelector);
                const searchContainer = search ? search.closest('div') : null;
                const searchHeight = searchContainer ? searchContainer.offsetHeight : 0;

                const visibleOptions = Array.from(menu.querySelectorAll(optionSelector))
                    .filter((option) => !option.classList.contains('hidden'));

                const optionCount = Math.min(maxVisibleOptions, visibleOptions.length);
                const optionsHeight = visibleOptions
                    .slice(0, optionCount)
                    .reduce((sum, option) => sum + option.offsetHeight, 0);

                const maxHeight = searchHeight + optionsHeight;

                if (maxHeight > 0) {
                    menu.style.maxHeight = `${maxHeight}px`;
                }
            };

            const resetMenu = (menu) => {
                if (!menu) {
                    return;
                }

                const search = menu.querySelector(searchSelector);
                if (search) {
                    search.value = '';
                }

                menu.querySelectorAll(optionSelector).forEach((option) => {
                    option.classList.remove('hidden');
                });

                menu.scrollTop = 0;
                setMenuMaxHeight(menu);
            };

            const closeAll = () => {
                document.querySelectorAll(rootSelector).forEach((root) => {
                    const menu = root.querySelector(menuSelector);
                    if (menu) {
                        resetMenu(menu);
                        menu.classList.add('hidden');
                    }
                });
            };

            const applyFilter = (menu, query) => {
                const q = (query || '').trim().toLowerCase();

                menu.querySelectorAll(optionSelector).forEach((option) => {
                    const text = (option.textContent || '').toLowerCase();
                    option.classList.toggle('hidden', q.length > 0 && !text.includes(q));
                });

                setMenuMaxHeight(menu);
            };

            document.addEventListener('click', (event) => {
                const toggle = event.target.closest(toggleSelector);
                const option = event.target.closest(optionSelector);

                if (toggle) {
                    event.preventDefault();
                    event.stopPropagation();

                    const root = toggle.closest(rootSelector);
                    const menu = root ? root.querySelector(menuSelector) : null;
                    if (!menu) {
                        return;
                    }

                    const shouldOpen = menu.classList.contains('hidden');
                    closeAll();
                    if (shouldOpen) {
                        menu.classList.remove('hidden');
                        menu.scrollTop = 0;
                        setMenuMaxHeight(menu);

                        const search = menu.querySelector(searchSelector);
                        if (search) {
                            search.focus();
                            search.select();
                        }
                    }

                    return;
                }

                if (option) {
                    const root = option.closest(rootSelector);
                    const menu = root ? root.querySelector(menuSelector) : null;
                    if (menu) {
                        resetMenu(menu);
                        menu.classList.add('hidden');
                    }
                    return;
                }

                if (event.target.closest(menuSelector)) {
                    return;
                }

                closeAll();
            }, true);

            document.addEventListener('input', (event) => {
                const search = event.target.closest(searchSelector);
                if (!search) {
                    return;
                }

                const root = search.closest(rootSelector);
                const menu = root ? root.querySelector(menuSelector) : null;
                if (!menu) {
                    return;
                }

                applyFilter(menu, search.value);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeAll();
                    return;
                }

                if (event.key === 'Enter' && event.target && event.target.closest(searchSelector)) {
                    event.preventDefault();
                }
            });

            document.addEventListener('livewire:navigated', () => {
                closeAll();
            });
        })();
    </script>
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
