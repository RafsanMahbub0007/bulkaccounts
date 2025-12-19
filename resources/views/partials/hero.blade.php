<section id="hero-stats-section" class="relative overflow-hidden text-white py-16 px-4">

    <!-- 🌌 Animated gradient blobs -->
    <div class="absolute inset-0 -z-20 overflow-hidden">
        <div
            class="absolute w-[700px] h-[700px] bg-gradient-to-tr from-blue-500/30 via-pink-400/20 to-purple-500/30 rounded-full blur-[250px] top-[-100px] left-1/4 animate-pulse-slow">
        </div>
        <div
            class="absolute w-[600px] h-[600px] bg-gradient-to-bl from-amber-400/20 via-blue-300/10 to-purple-400/20 rounded-full blur-[220px] bottom-[-100px] right-1/4 animate-pulse-slow">
        </div>
        <div
            class="absolute w-[500px] h-[500px] bg-gradient-radial from-white/10 via-blue-400/5 to-transparent rounded-full blur-[300px] top-[50%] left-[50%] transform -translate-x-1/2 -translate-y-1/2 animate-spin-slow">
        </div>
    </div>

    <!-- 🌌 Background texture overlay -->
    <div class="absolute inset-0 opacity-20">
        <img src="/img/soft-dark-texture.jpg" alt="Background Texture" class="w-full h-full object-cover" />
    </div>

    <!-- 🌌 Semi-transparent dark overlay -->
    <div class="absolute inset-0 bg-[#0a0e17]/60 backdrop-blur-sm"></div>

    <!-- Canvas for particles -->
    <canvas id="particle-bg" class="absolute inset-0 -z-10"></canvas>

    @php
        $banners = \App\Models\Banner::all();
        $categories = \App\Models\Category::orderBy('order','ASC')->get();
    @endphp

    @foreach ($banners as $banner)
        <div class="container mx-auto relative grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <!-- Hero Text Content -->
            <div class="space-y-10 opacity-0 translate-y-10" id="hero-content">
                <div>
                    <h1 class="text-6xl sm:text-6xl font-bold leading-tight tracking-tight">
                        <span class="text-blue-400">{{ $banner->main_title }}</span><br>
                    </h1>
                    <h3 class="text-4xl sm:text-4xl font-bold leading-tight tracking-tight">
                        <span class="text-gray-500">{{ $banner->sub_title }}</span>
                    </h3>
                    <p class="text-lg sm:text-lg text-gray-300 leading-relaxed max-w-xl mt-6">
                        {{ $banner->title_details }}
                    </p>
                </div>

                <!-- Search Form -->
                <form action="/search" method="GET" id="hero-search"
                    class="flex items-center bg-white/10 rounded-3xl shadow-2xl backdrop-blur-xl border border-white/10 focus-within:ring-2 focus-within:ring-blue-400/50 transition-all max-w-xl opacity-0 translate-y-10">
                    <input type="text" name="query" placeholder="Search verified accounts…"
                        class="flex-1 px-6 py-5 bg-transparent text-white rounded-l-3xl placeholder-gray-400 focus:outline-none text-lg" />
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 transition-all text-white px-10 py-5 font-semibold rounded-r-3xl shadow-lg hover:shadow-blue-500/30">
                        Search
                    </button>
                </form>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-6 pt-4 opacity-0 translate-y-10" id="hero-ctas">
                    <a href="{{ route('pricing') }}"
                        class="bg-blue-500 hover:bg-blue-600 px-14 py-5 rounded-xl font-bold shadow-lg shadow-blue-500/20 text-center transition transform hover:scale-105 hover:shadow-blue-500/40">
                        Shop Now
                    </a>
                    <a href="{{ route('contact') }}"
                        class="bg-white/10 hover:bg-white/20 text-white border border-white/10 px-14 py-5 rounded-xl font-bold shadow-lg text-center transition transform hover:scale-105">
                        Contact Us
                    </a>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="relative flex justify-center lg:justify-end opacity-0 scale-90" id="hero-image">
                <div
                    class="rounded-[2rem] overflow-hidden shadow-2xl border border-white/10 bg-white/5 backdrop-blur-2xl w-full max-w-3xl transform transition-transform duration-500">
                    <img src="{{ image_path($banner->banner_image) }}" alt="Product Preview"
                        class="w-full h-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b1120]/70 to-transparent"></div>
                </div>
                <div
                    class="absolute -z-10 w-80 h-80 bg-blue-500/20 blur-3xl rounded-full top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                </div>
            </div>
        </div>
    @endforeach

    <!-- Stats Section -->
    <div class="container mx-auto mt-28 relative z-10">
        <div class="text-center mb-12">
            <h2
                class="text-5xl sm:text-6xl font-extrabold tracking-tight bg-gradient-to-r from-blue-400 via-indigo-300 to-amber-300 bg-clip-text text-transparent drop-shadow-[0_0_25px_rgba(80,120,255,0.25)]">
                Our Divine Achievements
            </h2>
            <p class="text-gray-400 mt-4 text-lg max-w-2xl mx-auto leading-relaxed">
                A radiant path of trust, quality, and excellence — illuminated by our valued clients worldwide.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <!-- Stats Cards (same as your original) -->
            <div
                class="stat-card group relative bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-10 text-center shadow-[0_0_25px_rgba(255,255,255,0.05)] hover:shadow-[0_0_60px_rgba(80,140,255,0.25)] transition-all hover:-translate-y-2 duration-500 overflow-hidden">
                <div
                    class="absolute inset-0 opacity-0 group-hover:opacity-10 bg-gradient-to-br from-blue-400 via-indigo-400 to-cyan-300 blur-2xl transition-all">
                </div>
                <div
                    class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-blue-500 via-indigo-400 to-cyan-400 text-white shadow-lg group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-user-check text-3xl"></i>
                </div>
                <h3 class="text-5xl font-extrabold text-white counter" data-count="88500">0</h3>
                <p class="text-gray-400 mt-3 text-lg">Accounts Verified</p>
            </div>

            <div
                class="stat-card group relative bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-10 text-center shadow-[0_0_25px_rgba(255,255,255,0.05)] hover:shadow-[0_0_60px_rgba(140,100,255,0.25)] transition-all hover:-translate-y-2 duration-500 overflow-hidden">
                <div
                    class="absolute inset-0 opacity-0 group-hover:opacity-10 bg-gradient-to-br from-purple-400 via-indigo-400 to-blue-500 blur-2xl transition-all">
                </div>
                <div
                    class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-indigo-400 via-purple-500 to-blue-500 text-white shadow-lg group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-calendar-alt text-3xl"></i>
                </div>
                <h3 class="text-5xl font-extrabold text-white counter" data-count="11">0</h3>
                <p class="text-gray-400 mt-3 text-lg">Years of Experience</p>
            </div>

            <div
                class="stat-card group relative bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-10 text-center shadow-[0_0_25px_rgba(255,255,255,0.05)] hover:shadow-[0_0_60px_rgba(255,160,200,0.25)] transition-all hover:-translate-y-2 duration-500 overflow-hidden">
                <div
                    class="absolute inset-0 opacity-0 group-hover:opacity-10 bg-gradient-to-br from-amber-400 via-pink-400 to-purple-500 blur-2xl transition-all">
                </div>
                <div
                    class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-amber-400 via-pink-400 to-purple-500 text-white shadow-lg group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-smile text-3xl"></i>
                </div>
                <h3 class="text-5xl font-extrabold text-white counter" data-count="99">0</h3>
                <p class="text-gray-400 mt-3 text-lg">Client Satisfaction</p>
            </div>

            <div
                class="stat-card group relative bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-10 text-center shadow-[0_0_25px_rgba(255,255,255,0.05)] hover:shadow-[0_0_60px_rgba(255,200,120,0.25)] transition-all hover:-translate-y-2 duration-500 overflow-hidden">
                <div
                    class="absolute inset-0 opacity-0 group-hover:opacity-10 bg-gradient-to-br from-blue-300 via-amber-400 to-pink-400 blur-2xl transition-all">
                </div>
                <div
                    class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-blue-300 via-amber-400 to-pink-400 text-white shadow-lg group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-headset text-3xl"></i>
                </div>
                <h3 class="text-5xl font-extrabold text-white counter" data-count="24">0</h3>
                <p class="text-gray-400 mt-3 text-lg">24/7 Support</p>
            </div>
        </div>
    </div>

    <!-- 🌟 Popular Categories -->
    <div class="container mx-auto mt-32 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-14">
            <h2
                class="text-5xl sm:text-6xl font-extrabold tracking-tight bg-gradient-to-r from-pink-400 via-purple-400 to-cyan-400 bg-clip-text text-transparent">
                Popular Categories
            </h2>
            <p class="text-gray-400 mt-4 text-lg max-w-2xl mx-auto">
                Explore our most trusted and in-demand categories chosen by thousands of customers.
            </p>
        </div>

        <!-- Categories Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 justify-center">
    @foreach ($categories as $category)
        <a href="{{ route('category.details', $category->slug) }}"
           class="group relative rounded-3xl overflow-hidden shadow-2xl border border-white/10 bg-gradient-to-br from-white/5 via-white/10 to-white/5 backdrop-blur-xl transform transition-all duration-500 hover:-translate-y-3 hover:scale-105 hover:shadow-[0_20px_50px_rgba(120,120,255,0.25)]">

            <!-- Image -->
            <div class="relative aspect-[4/3] overflow-hidden rounded-3xl">
                <img src="{{ image_path($category->image) }}" alt="{{ $category->name }}" loading="lazy"
                     decoding="async"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />

                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

                <!-- Animated Shine -->
                <div class="absolute inset-0 pointer-events-none">
                    <div
                        class="absolute top-0 left-[-75%] w-[50%] h-full bg-gradient-to-r from-white/30 via-white/10 to-transparent transform rotate-12 animate-shine-slow">
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="absolute inset-0 flex flex-col justify-end p-6">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white group-hover:text-cyan-400 transition-all duration-500">
                    {{ $category->name }}
                </h3>

                <p class="text-sm sm:text-base text-gray-300 mt-2 line-clamp-3">
                    {{ $category->description ?? 'Browse premium verified products in this category.' }}
                </p>

                <span
                    class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-cyan-400 transition-transform duration-300 group-hover:translate-x-1">
                    Explore Category
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </div>

            <!-- Glow / Depth -->
            <div
                class="absolute -z-10 inset-0 opacity-0 group-hover:opacity-100 transition-all duration-700 blur-[60px] bg-gradient-to-br from-blue-400/40 via-purple-400/30 to-pink-400/30">
            </div>
            <!-- Subtle Overlay Pattern -->
            <div
                class="absolute inset-0 pointer-events-none bg-[url('/img/subtle-dots.png')] bg-repeat opacity-10">
            </div>
        </a>
    @endforeach
</div>

    </div>

</section>

<!-- 🌠 Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        gsap.registerPlugin(ScrollTrigger);

        // Hero animations
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: "#hero-stats-section",
                start: "top 80%"
            }
        });
        tl.to("#hero-content", {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power3.out"
            })
            .to("#hero-search", {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power3.out"
            }, "-=0.4")
            .to("#hero-ctas", {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power3.out"
            }, "-=0.4")
            .to("#hero-image", {
                opacity: 1,
                scale: 1,
                duration: 1,
                ease: "power3.out"
            }, "-=0.6");

        // Parallax image effect
        gsap.to("#hero-image", {
            yPercent: -10,
            ease: "none",
            scrollTrigger: {
                trigger: "#hero-stats-section",
                start: "top bottom",
                scrub: true
            }
        });

        // Counter animations
        document.querySelectorAll(".counter").forEach(counter => {
            const target = +counter.dataset.count;
            gsap.fromTo(counter, {
                innerText: 0
            }, {
                innerText: target,
                duration: 2.5,
                ease: "power2.out",
                snap: {
                    innerText: 1
                },
                scrollTrigger: {
                    trigger: counter,
                    start: "top 85%"
                },
                onUpdate() {
                    counter.innerText = Math.floor(counter.innerText) + (target >= 88500 ? "+" :
                        target >= 99 ? "%" : (target === 24 ? "/7" : ""));
                }
            });
        });

        // Particles
        const canvas = document.getElementById("particle-bg");
        const ctx = canvas.getContext("2d");
        const particles = [];
        const resizeCanvas = () => {
            canvas.width = window.innerWidth;
            canvas.height = document.querySelector("#hero-stats-section").offsetHeight;
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
                this.size = Math.random() * 2;
                this.speedY = 0.2 + Math.random() * 0.4;
                this.alpha = 0.2 + Math.random() * 0.5;
            }
            update() {
                this.y -= this.speedY;
                if (this.y < -5) this.reset();
            }
            draw() {
                ctx.beginPath();
                ctx.fillStyle = `rgba(180,220,255,${this.alpha})`;
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        for (let i = 0; i < 100; i++) particles.push(new Particle());
        (function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.update();
                p.draw();
            });
            requestAnimationFrame(animate);
        })();
    });
</script>

<!-- 🌠 Keyframes -->
<style>

    @keyframes pulse-slow {

        0%,
        100% {
            opacity: 0.7;
            transform: scale(1);
        }

        50% {
            opacity: 1;
            transform: scale(1.05);
        }
    }

    .animate-pulse-slow {
        animation: pulse-slow 8s ease-in-out infinite;
    }

    @keyframes spin-slow {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    .animate-spin-slow {
        animation: spin-slow 60s linear infinite;
    }
</style>
