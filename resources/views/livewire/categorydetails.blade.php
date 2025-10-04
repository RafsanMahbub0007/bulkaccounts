<section class="bg-gray-900 text-white relative overflow-hidden px-6 py-20">
    <div class="container mx-auto py-10">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Left Sidebar: Category Filter -->
           

            <!-- Right Side: Search, Sorting, and Product Listings -->
            <div class="lg:col-span-4">
                <!-- Product Listing -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($subcategories as $subcategory)
                        <div class="border border-gray-700 rounded-lg bg-gray-800 p-4">
                            <img src="{{ asset('/storage/' . $subcategory->product_image) }}" alt="{{ $subcategory->name }}"
                                class="w-full h-48 object-cover mb-4 rounded-lg">
                            <h3 class="text-xl font-semibold text-white text-center">{{ $subcategory->name }}</h3>
                           <p class="text-gray-400 text-center text-base">
                            {{ \Illuminate\Support\Str::words($subcategory->description, 8, '...') }}
                        </p>
                        <a href="{{route('subcategory.details',[$subcategory->category->slug, $subcategory->slug])}}"
                            class="block mt-6 bg-red-600 text-white text-center font-semibold py-3 px-6 rounded-full hover:bg-red-700 transition">
                            Explore Now
                        </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $subcategories->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
