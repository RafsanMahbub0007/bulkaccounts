<div x-data="{ open: false, activeIndex: @entangle('activeIndex') }"
     @mouseenter="if (window.innerWidth > 1024) open = true"
     @mouseleave="if (window.innerWidth > 1024) open = false"
     class="relative">

    <!-- Toggle -->
    <button class="flex items-center justify-between w-full lg:w-auto px-4 py-2 text-gray-200 hover:text-white transition font-medium">
        <span>Categories</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>

    <!-- Mega Menu -->
    <div x-show="open" x-transition.opacity.scale.origin.top
         class="absolute left-1/2 top-full -translate-x-1/4 mt-3 w-screen max-w-[1100px]
                bg-gray-900 border border-white/10 rounded-2xl shadow-2xl p-5 z-50 overflow-hidden">

        <!-- Search -->
        <div class="mb-4">
            <input wire:model.debounce.300ms="search" type="search" placeholder="Search categories or subcategories..."
                   class="w-full bg-gray-800 placeholder-gray-400 text-gray-100 px-4 py-2 rounded-md outline-none focus:ring-2 focus:ring-red-400 text-sm" />
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <!-- LEFT SIDE -->
            <nav class="col-span-1 bg-gray-900/60 rounded-lg p-3 max-h-[60vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700">
                @foreach ($this->filteredCategories as $index => $category)
                    <button
                        x-on:mouseenter="activeIndex = {{ $index }}; $wire.set('activeIndex', {{ $index }}, true)"
                        :class="activeIndex === {{ $index }} ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800'"
                        class="w-full text-left px-3 py-2 rounded-md flex items-center justify-between transition">
                        <span class="truncate font-medium">{{ $category['name'] }}</span>
                        <span class="ml-2 text-xs text-gray-400">{{ count($category['subcategories']) }}</span>
                    </button>
                @endforeach
            </nav>

            <!-- RIGHT SIDE -->
            <section class="col-span-4 bg-gray-800/30 rounded-lg p-4 max-h-[60vh] overflow-y-auto min-w-0"
                     wire:key="category-{{ $activeIndex }}">
                @php $activeCategory = $this->filteredCategories[$activeIndex] ?? null; @endphp

                @if ($search)
                    @php
                        $results = collect($this->filteredCategories)
                            ->flatMap(fn($cat) => $cat['subcategories'])
                            ->take(200);
                    @endphp

                    @if ($results->isNotEmpty())
                        <h3 class="text-xs text-red-400 font-semibold mb-3">Search results</h3>
                        <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                            @foreach ($results as $sub)
                                <li>
                                    <a href="{{ $sub['url'] }}"
                                       class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-700 transition truncate whitespace-normal break-words">
                                        {{ $sub['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 text-sm">No results found.</p>
                    @endif
                @elseif ($activeCategory)
                    <h3 class="text-xs text-red-400 font-semibold mb-3">{{ $activeCategory['name'] }}</h3>
                    <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach ($activeCategory['subcategories'] as $sub)
                            <li>
                                <a href="{{ $sub['url'] }}"
                                   class="block px-3 py-2 rounded-md text-sm text-gray-200 hover:bg-gray-700 transition truncate whitespace-normal break-words">
                                    {{ $sub['name'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-sm">Select a category to view subcategories.</p>
                @endif
            </section>
        </div>
    </div>
</div>
