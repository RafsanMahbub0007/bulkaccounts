<section class="bg-gray-900 text-white relative overflow-hidden px-6 py-20">

    <div class="container mx-auto">

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

            <!-- 🔹 RIGHT CONTENT AREA (NOW ON LEFT SIDE) -->
            <div class="lg:col-span-4">

                <!-- 🔸 SEARCH + TITLE -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500
                               bg-clip-text text-transparent">
                        Explore {{$category->name}}
                    </h2>
                </div>

                <!-- 🔹 PRODUCT GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">

                    @foreach ($subcategories as $subcategory)
                        <div
                            class="group bg-gray-800/60 backdrop-blur-md border border-white/10 rounded-3xl
                                   overflow-hidden shadow-xl transition-transform duration-300 hover:scale-[1.03]">

                            <!-- Image -->
                            <div class="relative">
                                <img src="{{ asset('storage/' . $subcategory->image) }}"
                                     alt="{{ $subcategory->name }}"
                                     class="w-full h-48 object-cover rounded-t-3xl transition duration-500
                                            group-hover:opacity-90">

                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0
                                            group-hover:opacity-80 transition"></div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-white mb-2 text-center">
                                    {{ $subcategory->name }}
                                </h3>

                                <p class="text-gray-400 text-sm text-center leading-relaxed">
                                    {{ Str::words($subcategory->description, 10, '...') }}
                                </p>

                                <a href="{{ route('subcategory.details', [$subcategory->category->slug, $subcategory->slug]) }}"
                                   class="block mt-6 text-center py-3 rounded-xl font-semibold
                                          bg-gradient-to-r from-pink-500 to-purple-500
                                          hover:from-pink-400 hover:to-purple-400 text-white
                                          shadow-lg shadow-pink-500/30 transition duration-300">
                                    Explore Now
                                </a>
                            </div>

                        </div>
                    @endforeach

                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $subcategories->links() }}
                </div>
            </div>

            <!-- 🔹 POPULAR CATEGORIES SIDEBAR (ENHANCED VERSION) -->
<aside class="lg:col-span-1 hidden lg:block sticky top-24
               bg-gray-800/40 backdrop-blur-xl
               border border-cyan-400/20 rounded-2xl p-6 h-fit
               shadow-[0_0_20px_rgba(34,211,238,0.15)] transition">

    <!-- Title -->
    <h2 class="text-xl font-extrabold mb-5
               bg-gradient-to-r from-cyan-400 to-blue-500
               text-transparent bg-clip-text drop-shadow-[0_0_10px_rgba(34,211,238,0.6)]">
        Other Popular Categories
    </h2>

    <!-- Category Items -->
    <div class="space-y-2">
        @foreach ($popularCategories as $popularCategory)
            <a href="{{ route('category.details', $popularCategory->slug) }}"
               class="block py-2.5 px-3 rounded-xl
                      bg-gray-700/20 border border-transparent
                      text-gray-300
                      hover:text-white
                      hover:bg-gray-700/40
                      hover:border-cyan-400/40
                      transition-all duration-300
                      hover:shadow-[0_0_12px_rgba(34,211,238,0.35)]">
                {{ $popularCategory->name }}
            </a>
        @endforeach
    </div>

</aside>


        </div>

    </div>

</section>
