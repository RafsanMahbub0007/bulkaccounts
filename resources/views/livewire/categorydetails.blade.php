<section class="relative bg-gray-900 text-white overflow-hidden px-6 py-20">

    <!-- Glow Background -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-cyan-500/25 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-0 right-0 w-[520px] h-[520px] bg-purple-600/25 rounded-full blur-[160px]"></div>
    </div>

    <div class="container mx-auto relative z-10">

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

            <!-- 🔹 MAIN CONTENT -->
            <div class="lg:col-span-4">

                <!-- TITLE -->
                <div class="mb-10">
                    <h2
                        class="text-3xl font-bold
                               bg-gradient-to-r from-cyan-400 to-blue-500
                               bg-clip-text text-transparent">
                        Explore {{ $category->name }}
                    </h2>
                </div>

                <!-- 🔹 SUBCATEGORY GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">

                    @foreach ($subcategories as $subcategory)
                        <div
                            class="group rounded-3xl overflow-hidden
                                   bg-gray-900/70 backdrop-blur-xl
                                   border border-white/10
                                   shadow-lg transition-all duration-300
                                   hover:scale-[1.03]
                                   hover:shadow-cyan-500/20">

                            <!-- Image -->
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ image_path($subcategory->image) }}" alt="{{ $subcategory->name }}"
                                    class="h-full w-full object-cover
                                            transition-transform duration-700
                                            group-hover:scale-60" />

                                <!-- Overlay -->
                                <div
                                    class="absolute inset-0
                                            bg-gradient-to-t from-black/70 to-transparent
                                            opacity-70">
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6 flex flex-col gap-4">

                                <h3 class="text-lg font-semibold text-cyan-400 text-center truncate">
                                    {{ $subcategory->name }}
                                </h3>

                                <p class="text-gray-400 text-sm text-center leading-relaxed">
                                    {{ Str::words($subcategory->description, 10, '...') }}
                                </p>

                                <a href="{{ route('subcategory.details', [$subcategory->category->slug, $subcategory->slug]) }}"
                                    class="mt-4 text-center py-2.5 rounded-xl font-semibold
                                          bg-gradient-to-r from-pink-500 to-purple-500
                                          hover:from-pink-400 hover:to-purple-400
                                          text-white
                                          shadow-lg shadow-pink-500/30
                                          transition-all duration-300
                                          hover:-translate-y-0.5">
                                    Explore Now
                                </a>

                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Pagination -->
                <div class="mt-10">
                    {{-- Pagination removed --}}
                </div>
            </div>

            <!-- 🔹 SIDEBAR -->
            <aside
                class="lg:col-span-1 hidden lg:block sticky top-24 h-fit
                       backdrop-blur-2xl bg-white/10
                       border border-white/10 rounded-2xl p-6
                       shadow-xl">

                <h2 class="text-xl font-semibold text-cyan-400 mb-5">
                    Other Popular Categories
                </h2>

                <div class="space-y-2">
                    @foreach ($popularCategories as $popularCategory)
                        <a href="{{ route('category.details', $popularCategory->slug) }}"
                            class="block px-4 py-2.5 rounded-xl
                                  text-gray-300
                                  bg-white/5
                                  border border-transparent
                                  transition-all duration-300
                                  hover:text-white
                                  hover:bg-white/10
                                  hover:border-cyan-400/40
                                  hover:shadow-[0_0_12px_rgba(34,211,238,0.35)]">
                            {{ $popularCategory->name }}
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
</section>
