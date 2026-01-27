@php
    $categories = \App\Models\Category::orderBy('order', 'ASC')->get();
@endphp

<!-- Popular Categories Section -->
<div class="container mx-auto mt-12 sm:mt-16 lg:mt-20 xl:mt-24 relative z-10 mb-20">
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
                                    View More
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