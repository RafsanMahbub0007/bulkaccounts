<li class="relative" x-data="{ catOpen: false }" @mouseenter="catOpen = true" @mouseleave="catOpen = false">

    <button class="flex items-center space-x-1 hover:text-white transition" @click="catOpen = !catOpen">
        <span>Categories</span>
        <i class="fas fa-chevron-down text-xs"></i>
    </button>

    <!-- Mega Menu -->
    <div x-show="catOpen" x-transition.opacity.scale.origin.top
        class="absolute left-0 top-full mt-3 w-[800px] bg-gray-800 border border-white/10 rounded-xl shadow-lg p-6 grid grid-cols-4 gap-6"
        @click.away="catOpen = false">

        @foreach ($categories as $category)
            <div>
                <h4 class="text-red-400 font-semibold mb-3">{{ $category->name }}</h4>
                <ul class="space-y-1">
                    @foreach ($category->subcategories as $sub)
                        <li>
                            <a href="{{ route('subcategory.details', ['category' => $category->slug, 'subcategory' => $sub->slug]) }}"
                                class="block px-2 py-2 rounded-md text-gray-300 hover:bg-gray-700 hover:text-white transition">
                                {{ $sub->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach


    </div>
</li>
