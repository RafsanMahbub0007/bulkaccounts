<section class="bg-gray-900 text-white relative overflow-hidden px-6 py-20">
    <div class="container mx-auto py-10">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <div class="lg:col-span-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <div class="border border-gray-700 rounded-lg bg-gray-800 p-4 hover:shadow-lg transition">

                            <img src="{{ asset('/storage/' . $product->product_image) }}" alt="{{ $product->name }}"
                                class="w-full h-48 object-cover mb-4 rounded-lg">
                            <h3 class="text-xl font-semibold text-white text-center">{{ $product->name }}</h3>
                            <div class="flex flex-wrap justify-center gap-2 mt-2">
                                @php
                                    $featureLabels = [
                                        '2fa_certified' => '2FA Certified',
                                        'mail_verified' => 'Mail Verified',
                                        'aged' => 'Aged',
                                        'brand_new' => 'Brand New',
                                        'eva' => 'EVA',
                                        'pva' => 'PVA',
                                    ];
                                    $featureColors = [
                                        '2fa_certified' => 'bg-blue-500',
                                        'mail_verified' => 'bg-green-500',
                                        'aged' => 'bg-yellow-500',
                                        'brand_new' => 'bg-purple-500',
                                        'eva' => 'bg-pink-500',
                                        'pva' => 'bg-red-500',
                                    ];
                                    $features = is_array($product->features) ? $product->features : json_decode($product->features, true);
                                @endphp

                                @foreach ($features ?? [] as $feature)
                                    <span class="text-white px-3 py-1 rounded-full text-sm {{ $featureColors[$feature] ?? 'bg-gray-500' }} shadow hover:scale-105 transition">
                                        {{ $featureLabels[$feature] ?? $feature }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="flex justify-center gap-4 mt-6 flex-wrap">
                                <button wire:click="addToCart({{ $product->id }})"
                                    class="bg-red-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-red-700 transition text-center">
                                    Explore Now
                                </button>
                                <a href="{{ route('product.details', $product->slug) }}"
                                    class="bg-red-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-red-700 transition text-center">
                                    Add to Cart
                                </a>
                                <a href="{{ route('product.details', $product->slug) }}"
                                    class="bg-red-600 text-white font-semibold py-3 px-6 rounded-full hover:bg-red-700 transition text-center">
                                    Buy Now
                                </a>
                            </div>
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
