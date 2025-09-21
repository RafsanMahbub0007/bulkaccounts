<section class="bg-gray-900 text-white relative overflow-hidden px-6 py-20">
    <div class="container mx-auto py-10">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Left Sidebar: Category Filter -->
            <div class="lg:col-span-1 mb-6">
                <div class="border p-4 rounded-lg bg-gray-800">
                    <h3 class="text-xl font-semibold text-red-500 mb-4">Filter by Category</h3>
                    @foreach ($categories as $category)
                        <div class="mb-2">
                            <label class="inline-flex items-center">
                                <x-input type="checkbox" wire:model.live="category" value="{{ $category->id }}" />
                                <span class="ml-2 text-gray-300">{{ $category->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Side: Search, Sorting, and Product Listings -->
            <div class="lg:col-span-4">
                <!-- Search and Sorting Box -->
                <div class="flex justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <!-- Search Box -->
                        <x-input type="text" class="p-2 w-full" placeholder="Search products..."
                            wire:model.live.debounce.500ms="search" />
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Sorting Box -->
                        <x-select wire:model.live="sortDirection">
                            <option value="asc">Price: Low to High</option>
                            <option value="desc">Price: High to Low</option>
                        </x-select>
                    </div>
                </div>

                <!-- Product Listing -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div class="border border-gray-700 rounded-lg bg-gray-800 p-4">
                            <img src="{{ asset('/storage/' . $product->product_image) }}" alt="{{ $product->name }}"
                                class="w-full h-48 object-cover mb-4 rounded-lg">
                            <h3 class="text-xl font-semibold text-white">{{ $product->name }}</h3>
                            <p class="text-lg font-bold text-red-500">{{ $product->price }} BDT</p>
                            <a href="{{ route('product.details', $product->slug) }}"
                                class="text-red-500 hover:underline mt-4 block">View Details</a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
