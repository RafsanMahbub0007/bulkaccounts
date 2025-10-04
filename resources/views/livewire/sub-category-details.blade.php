<section class="bg-gray-900 text-white relative overflow-hidden px-6 py-20">
    <div class="container mx-auto py-10">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            @foreach ($products as $product)
                <div class="lg:col-span-1 bg-gray-800 rounded-lg p-4 shadow hover:shadow-lg transition">
                    <!-- Product Image -->
                    <img src="{{ asset('/storage/' . $product->product_image) }}" alt="{{ $product->name }}"
                        class="w-full h-48 object-cover mb-4 rounded-lg">

                    <!-- Product Name -->
                    <h3 class="text-xl font-semibold text-white text-center">{{ $product->name }}</h3>

                    <!-- Product Features -->
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


                    <div class="flex flex-wrap justify-center gap-2 mt-2">
                        @foreach ($product->feature_ids ?? [] as $featureId)
                            @php
                                $randomColor = $featureColors[array_rand($featureColors)];
                            @endphp
                            <span class="text-white px-3 py-1 rounded-full text-sm {{ $randomColor }} shadow hover:scale-105 transition">
                                {{ \App\Models\ProductFeature::find($featureId)->name ?? 'Unknown Feature' }}
                            </span>
                        @endforeach
                    </div>


                    <!-- Action Buttons -->
                    <div class="flex justify-center gap-4 mt-6 flex-wrap">
                        <button wire:click="addToCart({{ $product->id }})"
                            class="bg-red-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-red-700 transition text-center">
                            Add to Cart
                        </button>
                        <a href="{{ route('product.details', $product->slug) }}"
                            class="bg-red-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-red-700 transition text-center">
                            Buy Now
                        </a>
                        <a href="{{ route('product.details', $product->slug) }}"
                            class="bg-red-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-red-700 transition text-center">
                            Explore Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</section>
