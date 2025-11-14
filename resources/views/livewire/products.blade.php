<section class="bg-gray-900 text-white relative overflow-hidden px-6 py-20">
    <div class="container mx-auto py-10">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
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

            <div class="lg:col-span-4">
                <div class="flex justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <x-input type="text" class="p-2 w-full" placeholder="Search products..."
                            wire:model.live.debounce.500ms="search" />
                    </div>
                    <div class="flex items-center space-x-4">
                        <x-select wire:model.live="sortDirection">
                            <option value="asc">Price: Low to High</option>
                            <option value="desc">Price: High to Low</option>
                        </x-select>
                    </div>
                </div>
                @php
                    $featureColors = [
                        'bg-blue-500',
                        'bg-green-500',
                        'bg-yellow-500',
                        'bg-purple-500',
                        'bg-pink-500',
                        'bg-red-500',
                        'bg-indigo-500',
                        'bg-teal-500',
                        'bg-orange-500',
                        'bg-cyan-500',
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div
                            class="border border-gray-700 rounded-2xl bg-gray-800 p-4 shadow-lg hover:shadow-2xl transition-all flex flex-col">

                            <div class="relative">
                                <img src="{{ image_path($product->subcategory->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover rounded-lg shadow-md">
                            </div>

                            <div class="flex flex-col flex-grow mt-4">
                                <h3 class="text-xl font-bold text-white text-center truncate capitalize">
                                    {{ $product->name }}</h3>

                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach ($product->feature_ids ?? [] as $featureId)
                                        @php
                                            $randomColor = $featureColors[array_rand($featureColors)];
                                            $featureName =
                                                \App\Models\ProductFeature::find($featureId)->name ?? 'Unknown';
                                        @endphp
                                        <span
                                            class="text-white px-2.5 py-0.5 rounded-full text-xs {{ $randomColor }} shadow-sm">
                                            {{ $featureName }}
                                        </span>
                                    @endforeach
                                </div>

                                <div class="flex flex-col items-center mt-4">
                                    <span class="text-3xl font-extrabold text-white">
                                        {{ $product->selling_price }} $
                                    </span>
                                    <span class="text-xs text-gray-300">Per Unit</span>
                                </div>
                            </div>

                            <a href="{{ route('product.details', $product->slug) }}"
                                class="mt-4 w-full text-center bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2 rounded-full font-medium shadow-md transition">
                                <i class="fas fa-shopping-cart mr-1"></i> add to Cart
                            </a>
                            <a href="{{ route('product.details', $product->slug) }}"
                                class="mt-4 w-full text-center bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-2 rounded-full font-medium shadow-md transition">
                                <i class="fas fa-eye mr-1"></i> View Details
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
