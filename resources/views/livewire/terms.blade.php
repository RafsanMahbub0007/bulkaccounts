<section class="relative py-24 bg-gray-900 text-white">
    <div class="container mx-auto px-4">
        <!-- Terms Section -->
        <div class="grid gap-8 ">
            @foreach ($terms as $term)
                <div class="space-y-6">
                    <!-- Title -->
                    <h3 class="text-2xl font-semibold text-white mb-3">
                        {{ $term->title}}
                    </h3>

                    <!-- Content (Rich Text) -->
                    <div class="prose prose-invert text-gray-300">
                        {!! $term->terms !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
