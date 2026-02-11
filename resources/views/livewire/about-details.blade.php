<section class="relative py-24 bg-gradient-to-r from-gray-900 to-gray-800 text-white overflow-hidden">
    @php
        $seo = \App\Models\SeoSetting::where('page_name', 'about')->first();
        $title = $seo->meta_title ?? 'About Us - ' . ($system->website_name ?? 'Jabed');
        $description = $seo->meta_description ?? 'Learn about our mission to empower businesses with secure and verified bulk accounts.';
        $keywords = $seo->meta_keywords ?? '';
    @endphp
    @section('title', $title)
    @section('description', $description)
    @section('keywords', $keywords)

    <div class="container mx-auto px-4 text-center">
        <!-- Main Heading -->
        <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-6 animate-fade-in">
            About Us
        </h1>
        <p class="text-lg md:text-xl text-gray-200 leading-relaxed max-w-3xl mx-auto animate-fade-in mb-12">
            Empowering your business with secure and verified bulk accounts for seamless growth and enhanced online
            presence.
        </p>
        @foreach ($about_details as $about)
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center mb-16 {{ $loop->iteration % 2 === 0 ? 'md:flex md:flex-row-reverse' : '' }}">
                <!-- Text Column -->
                <div data-aos="{{ $loop->iteration % 2 === 1 ? 'fade-right' : 'fade-left' }}">
                    <h2 class="text-4xl font-bold text-white mb-6 glow-effect">{{ $about->title }}</h2>
                    <p class="text-gray-400 leading-relaxed mb-6">
                        {!! $about->desctiption !!}
                    </p>
                </div>

                <!-- Image Column -->
                <div class="relative" data-aos="{{ $loop->iteration % 2 === 1 ? 'fade-left' : 'fade-right' }}">
                    <img src="{{ image_path($about->about_image) }}" alt="Who We Are"
                        class="rounded-lg shadow-lg z-10 relative">
                    <div
                        class="absolute inset-0 bg-gray-800 rounded-lg transform scale-105 -translate-x-4 translate-y-4">
                    </div>
                </div>
            </div>
        @endforeach





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

    <!-- Background Effects -->
    <div class="absolute inset-0 w-full h-full bg-pattern opacity-10 pointer-events-none"></div>
    <div
        class="absolute w-96 h-96 bg-gradient-to-r from-blue-900 to-blue-950 rounded-full blur-3xl opacity-20 -bottom-24 -left-20">
    </div>
</section>
