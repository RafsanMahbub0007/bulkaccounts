<div>
    @php
        $seo = \App\Models\SeoSetting::where('page_name', 'home')->first();
        $title = $seo->meta_title ?? 'Home - ' . ($system->website_name ?? 'PvaProseller');
        $description = $seo->meta_description ?? 'Welcome to ' . ($system->website_name ?? 'PvaProseller') . '. The best place to buy verified bulk accounts.';
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

    @push('schema')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": @json($system->website_name ?? 'Jabed'),
      "url": @json(url('/')),
      "logo": @json(asset('storage/' . ($system->logo ?? 'default-logo.png'))),
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": @json($system->phone),
        "contactType": "Customer Service"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": @json($system->website_name ?? 'Jabed'),
      "url": @json(url('/')),
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/pricing') }}?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    @endpush
</div>
