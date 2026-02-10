<section class="relative py-24 bg-gray-900 text-white">
    @php
        $seo = \App\Models\SeoSetting::where('page_name', 'terms')->first();
        $title = $seo->meta_title ?? 'Terms & Conditions';
        $description = $seo->meta_description ?? 'Read our terms and conditions for using our services.';
        $keywords = $seo->meta_keywords ?? '';
    @endphp
    @section('title', $title)
    @section('description', $description)
    @section('keywords', $keywords)

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
