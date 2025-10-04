<section class="bg-gray-900 py-24 relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
        <!-- Heading and Subtitle -->
        <div class="text-center mb-20">
            <h2 class="text-5xl md:text-6xl font-extrabold text-red-500 leading-tight tracking-tighter animate-fade-in">
                Featured Categories
            </h2>
            <p class="text-xl md:text-2xl text-gray-400 mt-4 max-w-3xl mx-auto animate-slide-in">
                Explore handpicked accounts tailored for your social media success. Start your journey now!
            </p>
        </div>

        <!-- Category Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-12">
            <!-- Category Card -->
            @foreach ($categories as $category)
                <div
                    class="relative bg-gray-800 rounded-3xl shadow-md hover:shadow-xl transform hover:scale-105 transition-all group border-t-4 border-red-600">
                    <div class="p-8">
                        <div
                            class="w-20 h-20 mx-auto flex items-center justify-center bg-red-500 text-white rounded-full shadow-lg mb-6 overflow-hidden">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image"
                                class="w-full h-full object-cover">
                        </div>
                        <h3 class="text-xl font-bold text-center text-white mb-3">{{ $category->name }}</h3>
                        <p class="text-gray-400 text-center text-base">
                            {{ \Illuminate\Support\Str::words($category->description, 8, '...') }}
                        </p>
                        <a href="{{route('category.details',$category->slug)}}")
                            class="block mt-6 bg-red-600 text-white text-center font-semibold py-3 px-6 rounded-full hover:bg-red-700 transition">
                            Explore Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
