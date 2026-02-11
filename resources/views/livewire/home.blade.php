<div>
    @php
        $seo = \App\Models\SeoSetting::where('page_name', 'home')->first();
        $title = $seo->meta_title ?? 'Home - ' . ($system->website_name ?? 'Jabed');
        $description = $seo->meta_description ?? 'Welcome to ' . ($system->website_name ?? 'Jabed') . '. The best place to buy verified bulk accounts.';
        $keywords = $seo->meta_keywords ?? '';
    @endphp
    @section('title', $title)
    @section('description', $description)
    @section('keywords', $keywords)

    @include('partials.hero')
    @include('partials.stats')
    @include('partials.categories')
    @include('partials.featured-categories')
    @include('partials.affordable-pricing')
    @include('partials.guaranteed-safety')
    @include('partials.global-availability')
    @include('partials.why-us')
    @include('partials.testimonials')
    @include('partials.cta')
</div>
