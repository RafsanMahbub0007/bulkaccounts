<section id="hero-stats-section" class="relative overflow-hidden text-white py-8 sm:py-12 lg:py-16 px-4 sm:px-6 lg:px-8">
    <!-- Optimized Background Elements -->
    <div class="absolute inset-0 -z-20 overflow-hidden">
        <!-- Gradient Blobs - Reduced blur for performance -->
        <div
            class="absolute w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] lg:w-[700px] lg:h-[700px] bg-gradient-to-tr from-blue-500/20 via-pink-400/10 to-purple-500/20 rounded-full blur-[80px] sm:blur-[150px] lg:blur-[250px] top-[-50px] sm:top-[-100px] left-1/4 animate-pulse-slow">
        </div>
        <div
            class="absolute w-[250px] h-[250px] sm:w-[400px] sm:h-[400px] lg:w-[600px] lg:h-[600px] bg-gradient-to-bl from-amber-400/10 via-blue-300/5 to-purple-400/10 rounded-full blur-[70px] sm:blur-[120px] lg:blur-[220px] bottom-[-50px] sm:bottom-[-100px] right-1/4 animate-pulse-slow">
        </div>
    </div>

    <!-- Background Texture - Optimized -->
    <div class="absolute inset-0 -z-10 opacity-20 pointer-events-none">
        <img src="{{ image_path('img/soft-dark-texture.jpg') }}" alt="Background Texture" class="w-full h-full object-cover"
            loading="lazy" decoding="async" fetchpriority="low" />
    </div>

    <!-- Dark Overlay -->
    <div class="absolute inset-0 -z-10 bg-transparent pointer-events-none"></div>

    @php
        $banners = \App\Models\Banner::all();
    @endphp

    @php
        $heroBannerData = $banners->map(fn ($banner) => [
            'main_title' => $banner->main_title,
            'sub_title' => $banner->sub_title,
            'title_details' => $banner->title_details,
            'image' => image_path($banner->banner_image),
        ])->values();

        $firstBanner = $banners->first();
    @endphp

    <div id="hero-carousel"
        data-hero-banners='@json($heroBannerData)'
        class="relative min-h-[520px] sm:min-h-[560px] lg:min-h-[520px]"
        aria-roledescription="carousel"
        aria-label="Hero banners">
        <div class="container mx-auto relative grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 lg:gap-20 items-center">
            <div class="space-y-6 sm:space-y-8 lg:space-y-10 hero-content">
                <div class="hero-fade transition-opacity duration-700 opacity-100">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight tracking-tight">
                        <span class="text-blue-400 block" data-hero-main-title>
                            {{ $firstBanner?->main_title }}
                        </span>
                    </h1>
                    <h3 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold leading-tight tracking-tight mt-2 sm:mt-3">
                        <span class="text-gray-400 sm:text-gray-500" data-hero-sub-title>
                            {{ $firstBanner?->sub_title }}
                        </span>
                    </h3>
                    <p class="text-sm sm:text-base lg:text-lg text-gray-300 leading-relaxed max-w-xl mt-4 sm:mt-6" data-hero-title-details>
                        {{ $firstBanner?->title_details }}
                    </p>
                    <p class="sr-only" data-hero-slide-label>
                        Slide 1 of {{ $banners->count() }}
                    </p>
                </div>

                <form action="{{ route('search') }}" method="GET"
                    class="flex items-center bg-white/10 rounded-2xl sm:rounded-3xl shadow-xl sm:shadow-2xl backdrop-blur-xl border border-white/10 focus-within:ring-2 focus-within:ring-blue-400/50 transition-all max-w-xl">
                    <input type="text" required name="query" placeholder="Search verified accounts…"
                        class="flex-1 px-4 sm:px-5 lg:px-6 py-3 sm:py-4 lg:py-5 bg-transparent text-white rounded-l-2xl sm:rounded-l-3xl placeholder-gray-400 focus:outline-none text-sm sm:text-base lg:text-lg w-full min-w-0"
                        aria-label="Search products" />
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 transition-all text-white px-6 sm:px-8 lg:px-10 py-3 sm:py-4 lg:py-5 font-semibold rounded-r-2xl sm:rounded-r-3xl shadow-lg hover:shadow-blue-500/30 whitespace-nowrap text-sm sm:text-base">
                        Search
                    </button>
                </form>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 lg:gap-6 pt-2 sm:pt-4 hero-ctas">
                    <a href="{{ route('pricing') }}"
                        class="bg-blue-500 hover:bg-blue-600 px-6 sm:px-10 lg:px-14 py-3 sm:py-4 lg:py-5 rounded-lg sm:rounded-xl font-bold shadow-lg shadow-blue-500/20 text-center transition transform hover:scale-105 hover:shadow-blue-500/40 text-sm sm:text-base"
                        aria-label="Shop Now">
                        Shop Now
                    </a>
                    <a href="{{ route('contact') }}"
                        class="bg-white/10 hover:bg-white/20 text-white border border-white/10 px-6 sm:px-10 lg:px-14 py-3 sm:py-4 lg:py-5 rounded-lg sm:rounded-xl font-bold shadow-lg text-center transition transform hover:scale-105 text-sm sm:text-base"
                        aria-label="Contact Us">
                        Contact Us
                    </a>
                </div>
            </div>

            <div class="relative flex justify-center lg:justify-end mt-8 lg:mt-0 hero-image">
                <div
                    class="hero-fade transition-opacity duration-700 opacity-100 rounded-xl sm:rounded-2xl lg:rounded-[2rem] overflow-hidden shadow-xl sm:shadow-2xl border border-white/10 bg-white/5 backdrop-blur-sm sm:backdrop-blur-lg lg:backdrop-blur-2xl w-full max-w-lg lg:max-w-3xl">
                    <img
                        src="{{ $firstBanner ? image_path($firstBanner->banner_image) : '' }}"
                        alt="Product Preview"
                        class="w-full h-auto aspect-video sm:aspect-auto object-cover"
                        loading="eager"
                        decoding="async"
                        fetchpriority="high"
                        sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                        srcset="{{ $firstBanner ? (image_path($firstBanner->banner_image) . '?w=400 400w,' . image_path($firstBanner->banner_image) . '?w=800 800w,' . image_path($firstBanner->banner_image) . '?w=1200 1200w') : '' }}"
                        data-hero-image
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b1120]/70 to-transparent"></div>
                </div>
                <div
                    class="absolute -z-10 w-40 h-40 sm:w-60 sm:h-60 lg:w-80 lg:h-80 bg-blue-500/20 blur-xl sm:blur-2xl lg:blur-3xl rounded-full top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                </div>
            </div>
        </div>

        @if ($banners->count() > 1)
            <button type="button"
                class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 z-10 p-2 sm:p-3 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 backdrop-blur-sm"
                data-hero-prev
                aria-label="Previous slide">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <button type="button"
                class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 z-10 p-2 sm:p-3 rounded-full bg-white/10 hover:bg-white/20 border border-white/10 backdrop-blur-sm"
                data-hero-next
                aria-label="Next slide">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <div class="absolute bottom-2 sm:bottom-4 left-1/2 -translate-x-1/2 z-10 flex items-center gap-2">
                @foreach ($banners as $banner)
                    <button type="button"
                        class="hero-dot w-2.5 h-2.5 rounded-full transition-all border border-white/30 {{ $loop->first ? 'bg-white' : 'bg-white/20 hover:bg-white/40' }}"
                        data-hero-dot="{{ $loop->index }}"
                        aria-label="Go to slide {{ $loop->iteration }}"
                        aria-current="{{ $loop->first ? 'true' : 'false' }}"></button>
                @endforeach
            </div>
        @endif
    </div>

</section>

<!-- Optimized JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        initHeroCarousel();

        // Only load GSAP if not already loaded
        if (!window.gsap) {
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js';
            script.onload = () => {
                // Load ScrollTrigger after GSAP
                const stScript = document.createElement('script');
                stScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js';
                stScript.onload = initAnimations;
                document.head.appendChild(stScript);
            };
            document.head.appendChild(script);
        } else {
            initAnimations();
        }

        function initHeroCarousel() {
            const root = document.getElementById('hero-carousel');
            if (!root) return;

            const data = root.dataset.heroBanners;
            if (!data) return;

            let banners;
            try {
                banners = JSON.parse(data);
            } catch {
                return;
            }

            if (!Array.isArray(banners) || banners.length <= 1) return;

            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const prevBtn = root.querySelector('[data-hero-prev]');
            const nextBtn = root.querySelector('[data-hero-next]');
            const dots = Array.from(root.querySelectorAll('[data-hero-dot]'));
            const fadeEls = Array.from(root.querySelectorAll('.hero-fade'));

            const mainTitleEl = root.querySelector('[data-hero-main-title]');
            const subTitleEl = root.querySelector('[data-hero-sub-title]');
            const detailsEl = root.querySelector('[data-hero-title-details]');
            const slideLabelEl = root.querySelector('[data-hero-slide-label]');
            const imgEl = root.querySelector('[data-hero-image]');

            let active = 0;
            let timer = null;

            const setActive = (index) => {
                active = (index + banners.length) % banners.length;
                const banner = banners[active];

                fadeEls.forEach((el) => el.classList.add('opacity-0'));

                window.setTimeout(() => {
                    if (mainTitleEl) mainTitleEl.textContent = banner?.main_title ?? '';
                    if (subTitleEl) subTitleEl.textContent = banner?.sub_title ?? '';
                    if (detailsEl) detailsEl.textContent = banner?.title_details ?? '';

                    if (slideLabelEl) {
                        slideLabelEl.textContent = `Slide ${active + 1} of ${banners.length}`;
                    }

                    if (imgEl && banner?.image) {
                        imgEl.src = banner.image;
                        imgEl.setAttribute('srcset', `${banner.image}?w=400 400w, ${banner.image}?w=800 800w, ${banner.image}?w=1200 1200w`);
                        imgEl.loading = 'lazy';
                        imgEl.fetchPriority = 'low';
                    }

                    fadeEls.forEach((el) => el.classList.remove('opacity-0'));
                }, 220);

                dots.forEach((dot, i) => {
                    if (i === active) {
                        dot.classList.remove('bg-white/20', 'hover:bg-white/40');
                        dot.classList.add('bg-white');
                        dot.setAttribute('aria-current', 'true');
                    } else {
                        dot.classList.remove('bg-white');
                        dot.classList.add('bg-white/20', 'hover:bg-white/40');
                        dot.setAttribute('aria-current', 'false');
                    }
                });
            };

            const stop = () => {
                if (timer) clearInterval(timer);
                timer = null;
            };

            const start = () => {
                stop();
                if (prefersReducedMotion) return;
                timer = setInterval(() => setActive(active + 1), 7000);
            };

            prevBtn?.addEventListener('click', () => setActive(active - 1));
            nextBtn?.addEventListener('click', () => setActive(active + 1));

            dots.forEach((dot) => {
                dot.addEventListener('click', () => setActive(parseInt(dot.dataset.heroDot ?? '0', 10)));
            });

            root.addEventListener('mouseenter', stop);
            root.addEventListener('mouseleave', start);

            setActive(0);
            start();
        }

        function initAnimations() {
            // Check if GSAP is available
            if (!window.gsap) return;

            gsap.registerPlugin(ScrollTrigger);

            // Hero animations with reduced motion support
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!prefersReducedMotion) {
                // Parallax effect
                gsap.to(".hero-image", {
                    yPercent: -5,
                    ease: "none",
                    scrollTrigger: {
                        trigger: "#hero-stats-section",
                        start: "top bottom",
                        end: "bottom top",
                        scrub: true,
                        markers: false // Remove in production
                    }
                });
            }

            // Counter animations - Only when in viewport
            document.querySelectorAll(".counter").forEach(counter => {
                const target = +counter.dataset.count;
                const suffix = target >= 88500 ? '+' : target >= 99 ? '%' : (target === 24 ? '/7' : '');

                ScrollTrigger.create({
                    trigger: counter,
                    start: "top 90%",
                    once: true,
                    onEnter: () => {
                        gsap.to(counter, {
                            innerText: target,
                            duration: prefersReducedMotion ? 1 : 2,
                            ease: "power2.out",
                            snap: {
                                innerText: 1
                            },
                            onUpdate: function() {
                                counter.innerText = Math.floor(this.targets()[0]
                                    .innerText) + suffix;
                            }
                        });
                    }
                });
            });

            // Simple particles for mobile (reduced count)
            if (!prefersReducedMotion && window.innerWidth > 768) {
                initParticles();
            }
        }

        function initParticles() {
            const canvas = document.getElementById("particle-bg");
            if (!canvas) return;

            const ctx = canvas.getContext("2d");
            const particles = [];
            const particleCount = window.innerWidth < 768 ? 30 : 70;

            const resizeCanvas = () => {
                const section = document.querySelector("#hero-stats-section");
                if (section) {
                    canvas.width = section.offsetWidth;
                    canvas.height = section.offsetHeight;
                }
            };

            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            class Particle {
                constructor() {
                    this.reset();
                }
                reset() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 1.5;
                    this.speedY = 0.1 + Math.random() * 0.3;
                    this.alpha = 0.1 + Math.random() * 0.3;
                }
                update() {
                    this.y -= this.speedY;
                    if (this.y < -10) this.reset();
                }
                draw() {
                    ctx.beginPath();
                    ctx.fillStyle = `rgba(180,220,255,${this.alpha})`;
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
            }

            // Create particles
            for (let i = 0; i < particleCount; i++) particles.push(new Particle());

            // Animation loop with requestAnimationFrame
            let animationId;

            function animate() {
                if (!canvas.width || !canvas.height) {
                    resizeCanvas();
                }

                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particles.forEach(p => {
                    p.update();
                    p.draw();
                });
                animationId = requestAnimationFrame(animate);
            }

            // Start animation
            animate();

            // Cleanup on page hide
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    cancelAnimationFrame(animationId);
                } else {
                    animate();
                }
            });
        }

    });
</script>

<!-- Optimized CSS -->
<style>
    /* Animations */
    @keyframes gradient {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    .animate-gradient {
        background-size: 200% auto;
        animation: gradient 3s ease infinite;
    }

    @keyframes shine {
        0% {
            left: -100%;
        }

        100% {
            left: 200%;
        }
    }

    .animate-shine {
        animation: shine 1.5s ease-out;
    }

    /* Category Navigation Buttons */
    .category-nav-btn {
        @apply p-2.5 rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all duration-300 disabled:opacity-30 disabled:cursor-not-allowed;
    }

    .category-dot.active {
        @apply bg-blue-500 scale-125;
    }

    /* Card Hover Effects */
    .category-card {
        transform-style: preserve-3d;
        perspective: 1000px;
    }

    .category-card:hover {
        transform: translateY(-8px) rotateX(2deg);
    }

    /* Progressive Enhancement */
    @media (prefers-reduced-motion: reduce) {

        .category-card,
        .category-card *,
        .animate-gradient,
        .animate-shine {
            animation: none !important;
            transition: none !important;
        }
    }

    /* Touch Device Optimizations */
    @media (pointer: coarse) {
        .category-card:hover {
            transform: none;
        }

        .quick-view-btn {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Adjustments */
    @media (max-width: 640px) {
        .category-card-wrapper {
            margin-bottom: 1.5rem;
        }

        .categories-container .grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }

    @media (min-width: 641px) and (max-width: 1024px) {
        .categories-container .grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
    }

    /* High Contrast Mode */
    @media (prefers-contrast: high) {
        .category-card {
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(0, 0, 0, 0.8);
        }
    }

    /* Dark Mode Support (already dark, but ensures compatibility) */
    @media (prefers-color-scheme: dark) {
        .category-card {
            background: rgba(17, 24, 39, 0.8);
        }
    }

    /* Reduced Motion Support */
    @media (prefers-reduced-motion: reduce) {

        .animate-pulse-slow,
        .animate-spin-slow,
        .stat-card,
        #hero-image,
        .group {
            animation: none !important;
            transition: none !important;
        }
    }

    /* Optimized Animations */
    @keyframes pulse-slow {

        0%,
        100% {
            opacity: 0.7;
            transform: scale(1);
        }

        50% {
            opacity: 1;
            transform: scale(1.02);
        }
    }

    .animate-pulse-slow {
        animation: pulse-slow 10s ease-in-out infinite;
    }

    /* Performance optimized hover effects */
    @media (hover: hover) and (pointer: fine) {
        .stat-card:hover {
            transform: translateY(-8px);
        }

        .group:hover img {
            transform: scale(1.05);
        }
    }

    /* Touch device optimizations */
    @media (pointer: coarse) {
        .stat-card:active {
            transform: translateY(-4px);
        }

        button,
        a {
            min-height: 44px;
            min-width: 44px;
        }
    }

    /* Responsive breakpoints */
    @media (max-width: 475px) {
        .xs\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        #hero-content h1 {
            font-size: 2rem;
        }
    }

    @media (max-width: 640px) {
        #hero-search {
            flex-direction: column;
            border-radius: 1rem;
        }

        #hero-search input {
            border-radius: 1rem 1rem 0 0;
            width: 100%;
        }

        #hero-search button {
            border-radius: 0 0 1rem 1rem;
            width: 100%;
        }

        .stat-card {
            padding: 1.5rem;
        }
    }

    /* Accessibility improvements */
    button:focus-visible,
    a:focus-visible,
    input:focus-visible {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }

    /* Image loading optimization */
    img {
        content-visibility: auto;
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* Print styles */
    @media print {

        .stat-card,
        #hero-image,
        .group {
            break-inside: avoid;
        }

        #hero-stats-section {
            color: black !important;
            background: white !important;
        }
    }

    /* High contrast mode */
    @media (prefers-contrast: high) {
        .text-gray-400 {
            color: #9ca3af;
        }

        .border-white\/10 {
            border-color: rgba(255, 255, 255, 0.3);
        }
    }
</style>
