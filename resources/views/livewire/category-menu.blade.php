<div class="relative"
    x-data="{ catOpen: false }"
    @mouseenter.window="if(window.innerWidth > 1024) catOpen = true"
    @mouseleave.window="if(window.innerWidth > 1024) catOpen = false">

    <!-- Toggle Button -->
    <button
        class="flex items-center justify-between w-full lg:w-auto space-x-1 hover:text-white transition"
        @click="catOpen = !catOpen">
        <span>Categories</span>
        <i class="fas fa-chevron-down text-xs"></i>
    </button>

    <!-- Mega Menu -->
    <div
        x-show="catOpen"
        x-transition.opacity.scale.origin.top
        @click.away="catOpen = false"
        class="absolute left-0 top-full mt-3 w-full lg:w-[800px] bg-gray-800 border border-white/10 rounded-xl shadow-lg p-4 sm:p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6 z-50">

        @foreach ($categories as $category)
            <div>
                <h4 class="text-red-400 font-semibold mb-3 text-sm sm:text-base">
                    {{ $category->name }}
                </h4>
                <ul class="space-y-1">
                    @foreach ($category->subcategories as $sub)
                        <li>
                            <a href="{{ route('subcategory.details', ['category' => $category->slug, 'subcategory' => $sub->slug]) }}"
                                class="block px-2 py-2 rounded-md text-gray-300 hover:bg-gray-700 hover:text-white transition text-sm sm:text-base">
                                {{ $sub->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach

    </div>
</div>
