<section class="relative bg-gray-900 py-32 overflow-hidden">
    <!-- Subtle Moving Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-tr from-purple-900 via-blue-900 to-gray-900 opacity-40 animate-pulse-slow pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <!-- Section Header -->
        <header class="text-center mb-20 max-w-3xl mx-auto">
            <h2 class="text-5xl md:text-6xl font-extrabold text-indigo-400 leading-tight tracking-tight animate-fade-in">
                Featured Products
            </h2>
            <p class="text-xl md:text-2xl text-gray-300 mt-4 animate-slide-in">
                Handpicked accounts curated to enhance your social media journey. Discover, engage, and grow today!
            </p>
        </header>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-12">
            @foreach ($categories as $category)
                <article class="relative group perspective rounded-3xl overflow-hidden shadow-xl transition-transform duration-500 hover:scale-105 hover:shadow-2xl">
                    <!-- Card Background Shapes -->
                    <div class="absolute -top-10 -left-10 w-36 h-36 bg-pink-500/20 rounded-full blur-3xl animate-blob will-change-transform"></div>
                    <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-cyan-400/20 rounded-full blur-3xl animate-blob animation-delay-2000 will-change-transform"></div>

                    <!-- Card Content -->
                    <div class="bg-stone-700 rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 group">
                        <!-- Image Section -->
                        <div class="relative w-full h-44 overflow-hidden border-b border-gray-200">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <span class="absolute top-3 left-3 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full">Featured</span>
                        </div>

                        <!-- Content Section -->
                        <div class="p-6 flex flex-col gap-3">
                            <h3 class="text-2xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">
                                {{ $category->name }}
                            </h3>
                            <span class="text-xl font-bold text-indigo-600">${{ number_format($category->price ?? 49.99, 2) }}</span>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ \Illuminate\Support\Str::words($category->description, 18, '...') }}
                            </p>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3 mt-4">
                                <a href="#" class="flex-1 text-center px-4 py-2 text-sm font-semibold text-white rounded-xl bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition-colors duration-300">
                                    Add to Cart
                                </a>
                                <a href="#" class="flex-1 text-center px-4 py-2 text-sm font-semibold text-white rounded-xl bg-cyan-500 hover:bg-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 transition-colors duration-300">
                                    Buy Now
                                </a>
                            </div>

                            <!-- Optional Tags/Info -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">New</span>
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Eco</span>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<style>
    /* Floating blob animation */
    @keyframes blob {
        0%, 100% { transform: translate3d(0,0,0) scale(1); }
        33% { transform: translate3d(15px,-10px,0) scale(1.05); }
        66% { transform: translate3d(-15px,10px,0) scale(0.95); }
    }
    .animate-blob { animation: blob 8s infinite ease-in-out; }

    .animation-delay-2000 { animation-delay: 2s; }

    /* Background pulse */
    @keyframes pulse-slow {
        0%,100% { opacity: 0.35; }
        50% { opacity: 0.5; }
    }
    .animate-pulse-slow { animation: pulse-slow 8s ease-in-out infinite; }

    /* Perspective hover tilt */
    .perspective { perspective: 1000px; }

    /* Fade/Slide animations */
    .animate-fade-in { animation: fadeIn 1.2s ease forwards; opacity:0; }
    .animate-slide-in { animation: slideIn 1s ease forwards; opacity:0; }

    @keyframes fadeIn { to { opacity: 1; } }
    @keyframes slideIn { to { opacity: 1; transform: translateY(0); } from { transform: translateY(20px); } }
</style>
