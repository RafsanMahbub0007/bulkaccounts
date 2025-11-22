<section class="relative bg-gray-900 text-white overflow-hidden px-6 py-20">

    <!-- Glow Background -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-pink-600/30 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[150px]"></div>
    </div>

    <div class="container mx-auto relative z-10 py-10">

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

            <!-- SIDEBAR FILTER -->
            <div class="lg:col-span-1">
                <div class="backdrop-blur-2xl bg-white/10 border border-white/10 p-6 rounded-2xl shadow-xl">
                    <h3 class="text-xl font-semibold text-pink-400 mb-4">Filter by Category</h3>

                    @foreach ($categories as $category)
                        <label class="flex items-center gap-3 mb-3 cursor-pointer">
                            <x-input type="checkbox" wire:model.live="category" value="{{ $category->id }}"
                                class="rounded-full border-pink-400 focus:ring-pink-500" />
                            <span class="text-gray-200">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- PRODUCTS -->
            <div class="lg:col-span-4">

                <!-- SEARCH + SORT -->
                <div class="flex justify-between items-center mb-8">

                    <x-input type="text" class="w-1/2 p-3 rounded-xl bg-gray-800 border-gray-700 text-white"
                        placeholder="Search products..."
                        wire:model.live.debounce.500ms="search" />

                    <x-select wire:model.live="sortDirection"
                        class="bg-gray-800 border-gray-700 text-white rounded-xl p-3">
                        <option value="asc">Price: Low → High</option>
                        <option value="desc">Price: High → Low</option>
                    </x-select>
                </div>

                <!-- PRODUCT GRID -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-8">

                    @foreach ($products as $product)
                        <div class="group backdrop-blur-xl bg-white/10 border border-white/10
                                    rounded-2xl shadow-lg p-5 flex flex-col transition-transform
                                    hover:scale-[1.03] hover:border-pink-500/40 hover:shadow-pink-500/20">

                            <!-- Image -->
                            <div class="relative rounded-xl overflow-hidden">
                                <img class="w-full h-48 object-cover"
                                     src="{{ image_path($product->subcategory->image) }}"
                                     alt="{{ $product->name }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                            </div>

                            <!-- Title -->
                            <h3 class="mt-4 text-lg font-bold text-center capitalize group-hover:text-pink-400 transition">
                                {{ $product->name }}
                            </h3>

                            <!-- Features -->
                            <div class="flex flex-wrap gap-2 justify-center mt-3">
                                @foreach ($product->featureList() as $feature)
                                    <span class="text-[11px] px-3 py-1 rounded-full font-semibold text-white
                                                 bg-white/10 border border-pink-400/40
                                                 shadow-[0_0_10px_#ec4899]
                                                 backdrop-blur-lg
                                                 hover:shadow-[0_0_20px_#ec4899]
                                                 transition">
                                        {{ $feature }}
                                    </span>
                                @endforeach
                            </div>

                            <!-- Price -->
                            <div class="mt-4 text-center">
                                <span class="text-3xl font-bold text-pink-400">
                                    {{ $product->selling_price }}$
                                </span>
                                <div class="text-xs text-gray-400">Per Unit</div>
                            </div>

                            <!-- Buttons -->
                            @livewire('add-to-cart', ['productId' => $product->id], key('product-' . $product->id))

                            <a href="{{ route('product.details', $product->slug) }}"
                                class="mt-2 w-full text-center bg-white/10 hover:bg-white/20
                                       border border-white/20 py-2 rounded-full text-white font-medium
                                       transition backdrop-blur-xl">
                                <i class="fas fa-eye mr-1"></i> View Details
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- PAGINATION -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
