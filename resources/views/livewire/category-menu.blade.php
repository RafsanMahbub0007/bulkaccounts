<div x-data="{
        open: false,
        activeIndex: @entangle('activeIndex'),
        mobileSearch: '',
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        }
    }"
    @mouseenter="if (window.innerWidth >= 1024) open = true"
    @mouseleave="if (window.innerWidth >= 1024) open = false"
    class="relative group font-sans">

    <!-- Trigger Button -->
    <button @click="toggle()"
            class="flex items-center gap-3 px-5 py-2.5 rounded-full transition-all duration-300
                   bg-white/5 hover:bg-white/10 border border-white/10 hover:border-red-500/30
                   text-gray-200 hover:text-white group-hover:shadow-[0_0_20px_rgba(239,68,68,0.15)]">
        <div class="p-1.5 rounded-full bg-gradient-to-tr from-red-500 to-red-600 text-white shadow-lg shadow-red-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </div>
        <span class="font-medium tracking-wide text-sm uppercase">Categories</span>
        <svg class="w-4 h-4 text-gray-400 transition-transform duration-300"
             :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         x-cloak
         class="absolute left-0 lg:left-1/2 lg:-translate-x-1/4 top-full mt-4 w-[95vw] lg:w-[1000px] max-w-[95vw]
                bg-[#0f111a] border border-white/10 rounded-2xl shadow-2xl shadow-black/50 z-50 overflow-hidden ring-1 ring-white/5">

        <!-- Decorative Header Line -->
        <div class="h-1 w-full bg-gradient-to-r from-red-600 via-purple-600 to-blue-600"></div>

        <div class="flex flex-col h-[80vh] lg:h-[600px]">

            <!-- Search Bar (Visible on all devices) -->
            <div class="p-4 border-b border-white/5 bg-white/[0.02]">
                <div class="relative max-w-md mx-auto lg:mx-0 lg:w-full">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input wire:model.debounce.300ms="search" type="text" placeholder="Find products, brands, and more..."
                           class="w-full bg-[#1a1d2d] text-gray-200 pl-12 pr-4 py-3 rounded-xl border border-white/10 focus:border-red-500/50 focus:ring-1 focus:ring-red-500/50 outline-none placeholder-gray-500 transition-all shadow-inner text-sm">
                </div>
            </div>

            <div class="flex-1 overflow-hidden relative">
                <!-- Desktop Layout (Split View) -->
                <div class="hidden lg:grid lg:grid-cols-12 h-full">

                    <!-- Sidebar (Categories) -->
                    <div class="col-span-3 bg-[#13151f] border-r border-white/5 overflow-y-auto custom-scrollbar">
                        <div class="flex flex-col p-3 gap-1">
                            @foreach ($this->filteredCategories as $index => $category)
                                <button @mouseover="activeIndex = {{ $index }}; $wire.set('activeIndex', {{ $index }})"
                                        class="flex items-center justify-between w-full px-4 py-3.5 rounded-xl text-left transition-all duration-200 group/item relative overflow-hidden"
                                        :class="activeIndex === {{ $index }} ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg shadow-red-900/20' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200'">

                                    <span class="relative z-10 text-sm font-medium tracking-wide truncate pr-2">{{ $category['name'] }}</span>

                                    <!-- Arrow Icon -->
                                    <svg class="w-4 h-4 relative z-10 opacity-0 -translate-x-2 transition-all duration-300"
                                         :class="activeIndex === {{ $index }} ? 'opacity-100 translate-x-0' : 'group-hover/item:opacity-50 group-hover/item:translate-x-0'"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Content Area (Subcategories) -->
                    <div class="col-span-9 bg-[#0f111a] p-8 overflow-y-auto custom-scrollbar relative">
                        <!-- Background decoration -->
                        <div class="absolute top-0 right-0 w-96 h-96 bg-red-600/5 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>

                        <div class="relative z-10">
                            @php $activeCategory = $this->filteredCategories[$activeIndex] ?? null; @endphp

                            @if ($search)
                                <!-- Search Results View -->
                                @php
                                    $results = collect($this->filteredCategories)->flatMap(fn($cat) => $cat['subcategories'])->take(50);
                                @endphp
                                <div class="mb-6">
                                    <h3 class="text-sm font-bold text-red-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        Search Results
                                    </h3>
                                    @if ($results->isNotEmpty())
                                        <div class="grid grid-cols-3 gap-4">
                                            @foreach ($results as $sub)
                                                <a href="{{ $sub['url'] }}" class="block p-4 rounded-xl bg-white/5 border border-white/5 hover:border-red-500/30 hover:bg-white/[0.07] transition-all group">
                                                    <span class="text-sm text-gray-300 group-hover:text-white">{{ $sub['name'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">No matching subcategories found.</p>
                                    @endif
                                </div>
                            @elseif ($activeCategory)
                                <!-- Category Details View -->
                                <div class="mb-8 flex items-end justify-between border-b border-white/10 pb-6">
                                    <div>
                                        <h2 class="text-3xl font-bold text-white mb-2">{{ $activeCategory['name'] }}</h2>
                                        <p class="text-gray-400 text-sm">Explore our collection of {{ count($activeCategory['subcategories']) }} subcategories</p>
                                    </div>
                                    <a href="#" class="text-xs font-bold text-red-400 hover:text-red-300 uppercase tracking-widest flex items-center gap-1 transition-colors">
                                        View All
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </a>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    @foreach ($activeCategory['subcategories'] as $sub)
                                        <a href="{{ $sub['url'] }}"
                                           class="group relative overflow-hidden p-4 rounded-xl bg-[#151824] border border-white/5 hover:border-red-500/30 transition-all duration-300 hover:shadow-lg hover:shadow-black/20 hover:-translate-y-1">
                                            <div class="relative z-10 flex flex-col h-full justify-between gap-3">
                                                <span class="text-sm font-medium text-gray-300 group-hover:text-white leading-relaxed">{{ $sub['name'] }}</span>
                                                <div class="w-6 h-0.5 bg-red-500/30 group-hover:bg-red-500 group-hover:w-full transition-all duration-500"></div>
                                            </div>
                                            <!-- Hover Gradient -->
                                            <div class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent to-red-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-gray-600">
                                    <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    <p>Select a category to start browsing</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mobile Layout (Accordion Stack) -->
                <div class="lg:hidden h-full overflow-y-auto custom-scrollbar bg-[#0f111a] p-4">
                    <div class="space-y-3">
                        @foreach ($this->filteredCategories as $index => $category)
                            <div x-data="{ expanded: activeIndex === {{ $index }} }" class="rounded-xl bg-white/5 border border-white/5 overflow-hidden">
                                <button @click="expanded = !expanded; if(expanded) { activeIndex = {{ $index }}; $wire.set('activeIndex', {{ $index }}) }"
                                        class="w-full flex items-center justify-between p-4 text-left transition-colors"
                                        :class="expanded ? 'bg-white/5 text-white' : 'text-gray-300'">
                                    <span class="font-medium text-sm">{{ $category['name'] }}</span>
                                    <svg class="w-4 h-4 transition-transform duration-300 text-gray-500"
                                         :class="expanded ? 'rotate-180 text-red-400' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div x-show="expanded" x-collapse class="border-t border-white/5 bg-black/20">
                                    <div class="p-3 grid grid-cols-2 gap-2">
                                        @foreach ($category['subcategories'] as $sub)
                                            <a href="{{ $sub['url'] }}" class="text-xs text-gray-400 hover:text-white p-2 rounded-lg hover:bg-white/5 transition-colors truncate block">
                                                {{ $sub['name'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom Scrollbar for sleek look */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</div>
