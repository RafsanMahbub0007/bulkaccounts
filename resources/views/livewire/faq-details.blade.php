<section class="relative py-20 bg-gray-900 text-white">
    @php
        $seo = \App\Models\SeoSetting::where('page_name', 'faq')->first();
        $title = $seo->meta_title ?? 'FAQ - ' . ($system->website_name ?? 'Jabed');
        $description = $seo->meta_description ?? 'Find answers to common questions about our bulk account services and support.';
        $keywords = $seo->meta_keywords ?? '';
    @endphp
    @section('title', $title)
    @section('description', $description)
    @section('keywords', $keywords)

    <div class="container mx-auto px-4">
        <!-- Hero / Header -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-white mb-4">
                Frequently Asked Questions
            </h1>
            <p class="text-lg md:text-xl text-gray-400 leading-relaxed max-w-2xl mx-auto">
                Find answers to the most common questions about our services and how we can help you achieve your goals.
            </p>
        </div>

        <!-- FAQ Section -->
        <div class="mb-16">
            <h2 class="text-4xl font-bold text-center text-white mb-12">
                Your Questions, Answered
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                @foreach ($faqdetails as $faq)
                    <div class="bg-gray-800 p-8 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 flex items-center justify-center flex-shrink-0 bg-red-500 text-white rounded-full shadow-lg hover:scale-110 transition">
                                <i class="fas fa-question-circle text-lg"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white ml-4">
                                {{ $faq->question }}
                            </h3>
                        </div>
                        <p class="text-gray-400 leading-relaxed">
                            {{ $faq->answer }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center">
            <h2 class="text-4xl font-bold mb-6">Still Have Questions?</h2>
            <p class="text-lg md:text-xl mb-8">
                Can’t find the answer you’re looking for? Contact us, and we’ll be happy to help.
            </p>
            <a href="/contact"
                class="bg-red-500 text-white py-3 px-8 rounded-full font-bold shadow-lg hover:bg-red-600 transition-transform transform hover:scale-110">
                Contact Us
            </a>
        </div>
    </div>
</section>
