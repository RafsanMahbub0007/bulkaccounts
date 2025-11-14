<section class="relative py-24 bg-gray-900 text-white">
    <div class="container mx-auto ">
        <!-- Hero / Header -->
        <div class="text-center mb-20">
            <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-4">
                Terms & Conditions
            </h1>
            <p class="text-lg md:text-xl text-gray-400 leading-relaxed max-w-3xl mx-auto">
                Clear and simple rules to help you get the most out of your experience.
            </p>
        </div>

        <!-- Terms Section -->
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($terms as $term)
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-2xl font-semibold text-white mb-4">
                        {{ $term->title ?? 'Rule' }}
                    </h3>
                    <p class="text-gray-300 leading-relaxed">
                        {!! $term->terms !!}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
