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
    <div class="absolute inset-0 opacity-20">
        <img src="/img/soft-dark-texture.jpg" alt="Background Texture" class="w-full h-full object-cover" loading="lazy"
            decoding="async" fetchpriority="low" />
    </div>

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-[#0a0e17]/60 backdrop-blur-sm"></div>

    @php
        $banners = \App\Models\Banner::all();
        $categories = \App\Models\Category::orderBy('order', 'ASC')->get();
    @endphp

    @foreach ($banners as $banner)
        <div class="container mx-auto relative grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 lg:gap-20 items-center">
            <!-- Hero Text Content -->
            <div class="space-y-6 sm:space-y-8 lg:space-y-10" id="hero-content">
                <div>
                    <!-- Responsive Typography -->
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight tracking-tight">
                        <span class="text-blue-400 block">{{ $banner->main_title }}</span>
                    </h1>
                    <h3
                        class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold leading-tight tracking-tight mt-2 sm:mt-3">
                        <span class="text-gray-400 sm:text-gray-500">{{ $banner->sub_title }}</span>
                    </h3>
                    <p class="text-sm sm:text-base lg:text-lg text-gray-300 leading-relaxed max-w-xl mt-4 sm:mt-6">
                        {{ $banner->title_details }}
                    </p>
                </div>

                <!-- Search Form - Responsive -->
                <form action="{{ route('search') }}" method="GET" id="hero-search"
                    class="flex items-center bg-white/10 rounded-2xl sm:rounded-3xl shadow-xl sm:shadow-2xl backdrop-blur-xl border border-white/10 focus-within:ring-2 focus-within:ring-blue-400/50 transition-all max-w-xl">
                    <input type="text" name="query" placeholder="Search verified accounts…"
                        class="flex-1 px-4 sm:px-5 lg:px-6 py-3 sm:py-4 lg:py-5 bg-transparent text-white rounded-l-2xl sm:rounded-l-3xl placeholder-gray-400 focus:outline-none text-sm sm:text-base lg:text-lg w-full min-w-0"
                        aria-label="Search products" />
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 transition-all text-white px-6 sm:px-8 lg:px-10 py-3 sm:py-4 lg:py-5 font-semibold rounded-r-2xl sm:rounded-r-3xl shadow-lg hover:shadow-blue-500/30 whitespace-nowrap text-sm sm:text-base">
                        Search
                    </button>
                </form>

                <!-- CTA Buttons - Responsive Inline -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 lg:gap-6 pt-2 sm:pt-4" id="hero-ctas">
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

            <!-- Hero Image - Responsive -->
            <div class="relative flex justify-center lg:justify-end mt-8 lg:mt-0" id="hero-image">
                <div
                    class="rounded-xl sm:rounded-2xl lg:rounded-[2rem] overflow-hidden shadow-xl sm:shadow-2xl border border-white/10 bg-white/5 backdrop-blur-sm sm:backdrop-blur-lg lg:backdrop-blur-2xl w-full max-w-lg lg:max-w-3xl">
                    <img src="{{ image_path($banner->banner_image) }}" alt="Product Preview"
                        class="w-full h-auto aspect-video sm:aspect-auto object-cover" loading="lazy" decoding="async"
                        sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                        srcset="{{ image_path($banner->banner_image) }}?w=400 400w,
                                {{ image_path($banner->banner_image) }}?w=800 800w,
                                {{ image_path($banner->banner_image) }}?w=1200 1200w" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b1120]/70 to-transparent"></div>
                </div>
                <div
                    class="absolute -z-10 w-40 h-40 sm:w-60 sm:h-60 lg:w-80 lg:h-80 bg-blue-500/20 blur-xl sm:blur-2xl lg:blur-3xl rounded-full top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                </div>
            </div>
        </div>
    @endforeach

    <!-- Stats Section -->
    <div class="container mx-auto mt-16 sm:mt-20 lg:mt-28 relative z-10">
        <div class="text-center mb-8 sm:mb-10 lg:mb-12">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold tracking-tight">
                <span class="bg-gradient-to-r from-blue-400 via-indigo-300 to-amber-300 bg-clip-text text-transparent">
                    Our Divine Achievements
                </span>
            </h2>
            <p
                class="text-gray-400 mt-2 sm:mt-3 lg:mt-4 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto leading-relaxed px-4">
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

    <!-- Popular Categories Section -->
    <div class="container mx-auto mt-12 sm:mt-16 lg:mt-20 xl:mt-24 relative z-10">
        <!-- Section Header with Interactive Controls -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 sm:mb-10 lg:mb-12 xl:mb-14 gap-4 sm:gap-6">
            <div class="text-center sm:text-left">
                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-extrabold tracking-tight mb-2">
                    <span
                        class="bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-500 bg-clip-text text-transparent animate-gradient">
                        Explore Categories
                    </span>
                </h2>
                <p class="text-gray-400 text-sm sm:text-base lg:text-lg max-w-xl">
                    Discover our curated collection of premium categories
                </p>
            </div>
        </div>

        <!-- Categories Grid - Interactive Design -->
        <div class="categories-container relative">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 xl:gap-10"
                x-data="{
                    activeCategory: null,
                    setActive(id) {
                        this.activeCategory = this.activeCategory === id ? null : id
                    }
                }">
                @foreach ($categories as $category)
                    <div class="category-card-wrapper" x-on:mouseenter="setActive({{ $category->id }})"
                        x-on:mouseleave="setActive(null)">
                        <a href="{{ route('category.details', $category->slug) }}"
                            class="category-card group relative block rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl border border-white/10 bg-gradient-to-br from-gray-900/50 to-gray-800/30 backdrop-blur-sm transition-all duration-500 hover:border-blue-500/30 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                            aria-label="Browse {{ $category->name }} category"
                            :class="{ 'scale-[0.99]': activeCategory === {{ $category->id }} }">

                            <!-- Background Pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <div
                                    class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(120,119,198,0.3),transparent_50%)]">
                                </div>
                            </div>

                            <!-- Image Container with Parallax Effect -->
                            <div class="relative h-48 sm:h-56 lg:h-64 overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent z-10">
                                </div>

                                <!-- Main Image -->
                                <img src="{{ image_path($category->image) }}" alt="{{ $category->name }}"
                                    loading="lazy" decoding="async"
                                    class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110"
                                    x-bind:class="{ 'scale-110': activeCategory === {{ $category->id }} }" />

                                <!-- Floating Elements -->
                                <div class="absolute top-4 right-4 z-20">
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-gradient-to-r from-blue-500/90 to-purple-500/90 text-white text-xs font-bold rounded-full backdrop-blur-sm">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Popular
                                    </span>
                                </div>

                                <!-- Shine Effect -->
                                <div
                                    class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                                    <div
                                        class="absolute top-0 left-[-100%] w-[50%] h-full bg-gradient-to-r from-transparent via-white/20 to-transparent transform rotate-12 animate-shine">
                                    </div>
                                </div>
                            </div>

                            <!-- Content Container -->
                            <div class="relative z-20 p-4 sm:p-5 lg:p-6">
                                <!-- Category Header -->
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div class="flex-1">
                                        <h3
                                            class="text-sm sm:text-sm lg:text-lg font-bold text-white group-hover:text-cyan-300 transition-colors duration-300 line-clamp-1">
                                            {{ $category->name }}
                                        </h3>

                                        <!-- Stats Badge -->
                                        <div class="flex items-center gap-3 mt-2">
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-500/20 text-blue-300 text-xs rounded-full">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                    </path>
                                                </svg>
                                                4.8
                                            </span>
                                            <span class="text-xs text-gray-400">•</span>
                                            <span class="text-xs text-gray-400">250+ Products</span>
                                        </div>
                                    </div>

                                    <!-- Quick Action Button -->
                                    <button
                                        class="quick-view-btn opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 p-2 rounded-full bg-white/10 hover:bg-white/20 border border-white/20"
                                        aria-label="Quick preview of {{ $category->name }}"
                                        @click.prevent="showQuickView({{ $category->id }})">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Features List -->
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach (['Premium', 'Verified', '24/7 Support', 'Fast Delivery'] as $feature)
                                            <span
                                                class="px-2 py-1 bg-white/5 border border-white/10 text-gray-300 text-xs rounded-full hover:bg-white/10 transition-colors duration-300">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Action Footer -->
                                <div class="flex items-center justify-between pt-4 border-t border-white/10">
                                    <span
                                        class="inline-flex items-center gap-2 text-sm font-semibold text-cyan-400 transition-transform duration-300 group-hover:translate-x-1">
                                        Explore Now
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </span>

                                    <!-- Price Range Indicator -->
                                    
                                </div>
                            </div>

                            <!-- Hover Glow Effect -->
                            <div
                                class="absolute -z-10 inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-2xl bg-gradient-to-br from-blue-500/20 via-purple-500/15 to-cyan-500/20">
                            </div>

                            <!-- Corner Accents -->
                            <div
                                class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-blue-500/30 rounded-tl-2xl">
                            </div>
                            <div
                                class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-purple-500/30 rounded-br-2xl">
                            </div>
                        </a>

                        <!-- Progress Indicator -->
                        <div class="mt-3">
                            <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full w-3/4">
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>Popularity</span>
                                <span>75%</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick View Modal (Hidden by default) -->
    <div id="category-quick-view" class="fixed inset-0 z-50 hidden" x-data="{ open: false }">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative h-full flex items-center justify-center p-4">
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-white/10"
                @click.stop>
                <!-- Modal content will be loaded dynamically -->
            </div>
        </div>
    </div>
</section>

<!-- Optimized JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
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

        function initAnimations() {
            // Check if GSAP is available
            if (!window.gsap) return;

            gsap.registerPlugin(ScrollTrigger);

            // Hero animations with reduced motion support
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!prefersReducedMotion) {
                // Parallax effect
                gsap.to("#hero-image", {
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
