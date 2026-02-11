<section class="relative py-24 bg-gradient-to-r from-gray-900 to-gray-800 text-white overflow-hidden">
    @php
        $seo = \App\Models\SeoSetting::where('page_name', 'privacy')->first();
        $title = $seo->meta_title ?? 'Privacy Policy';
        $description = $seo->meta_description ?? 'Read our privacy policy to understand how we collect, use, and protect your data.';
        $keywords = $seo->meta_keywords ?? '';
    @endphp
    @section('title', $title)
    @section('description', $description)
    @section('keywords', $keywords)

    <!-- Background Effects -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-gradient-to-r from-red-600 to-pink-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute top-1/4 right-0 w-72 h-72 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full blur-2xl opacity-25"></div>
        <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-gradient-to-r from-green-400 to-teal-500 rounded-full blur-3xl opacity-15"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 text-center">

        <!-- Policies -->
        @foreach ($policies as $policy)
            <div class="max-w-4xl mx-auto mb-16 text-left">
                <div class="prose prose-invert max-w-none">
                    {!! $policy->desctiption !!}
                </div>
            </div>
        @endforeach

        <!-- Values & Commitment -->
        <div class="mb-20">
            <h2 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-pink-500 mb-6 glow-effect">
                Our Values & Commitment
            </h2>
            <p class="text-lg md:text-xl text-gray-400 mb-12 max-w-3xl mx-auto">
                Discover the principles that drive us to deliver exceptional services and ensure your success.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

                <!-- Integrity -->
                <div class="flex flex-col items-center text-center" data-aos="fade-up">
                    <div class="flex items-center justify-center w-20 h-20 bg-gradient-to-tr from-red-500 to-pink-500 rounded-full shadow-lg mb-5">
                        <i class="fas fa-check-circle text-4xl text-white"></i>
                    </div>
                    <h4 class="text-2xl font-semibold text-white mb-3">Integrity</h4>
                    <p class="text-gray-400 leading-relaxed max-w-xs">
                        We uphold the highest standards of integrity in every aspect of our work, ensuring our clients receive honest, transparent, and dependable service.
                    </p>
                </div>

                <!-- Security -->
                <div class="flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-center w-20 h-20 bg-gradient-to-tr from-blue-500 to-purple-500 rounded-full shadow-lg mb-5">
                        <i class="fas fa-shield-alt text-4xl text-white"></i>
                    </div>
                    <h4 class="text-2xl font-semibold text-white mb-3">Security</h4>
                    <p class="text-gray-400 leading-relaxed max-w-xs">
                        Our clients' security is our priority. Every account we deliver is verified, reliable, and protected by the latest standards.
                    </p>
                </div>

                <!-- Customer Focus -->
                <div class="flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-center w-20 h-20 bg-gradient-to-tr from-green-400 to-teal-500 rounded-full shadow-lg mb-5">
                        <i class="fas fa-heart text-4xl text-white"></i>
                    </div>
                    <h4 class="text-2xl font-semibold text-white mb-3">Customer Focus</h4>
                    <p class="text-gray-400 leading-relaxed max-w-xs">
                        We are dedicated to our clients' success. Your growth is our growth, and we go the extra mile to provide exceptional support and tailored solutions.
                    </p>
                </div>

            </div>
        </div>

        <!-- Call to Action -->
        <div class="relative z-10" data-aos="zoom-in">
            <h2 class="text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500 mb-6 glow-effect">
                Boost Your Business Today
            </h2>
            <p class="text-lg md:text-xl text-gray-400 mb-12 max-w-3xl mx-auto">
                Get premium social media accounts in bulk, verified and ready to elevate your digital presence.
            </p>
            <a href="{{ route('home') }}"
                class="inline-block bg-gradient-to-r from-red-500 to-pink-500 text-white py-4 px-12 rounded-full font-bold shadow-xl transform hover:scale-110 hover:shadow-[0_0_40px_rgba(255,0,120,0.7)] transition-all duration-300">
                Browse Accounts
            </a>
        </div>

    </div>

</section>
