<section class="relative py-24 bg-gradient-to-r from-gray-900 to-gray-800 text-white overflow-hidden">
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
                    <img src="{{ asset('/storage/' . $about->about_image) }}" alt="Who We Are"
                        class="rounded-lg shadow-lg z-10 relative">
                    <div
                        class="absolute inset-0 bg-gray-800 rounded-lg transform scale-105 -translate-x-4 translate-y-4">
                    </div>
                </div>
            </div>
        @endforeach





        <!-- Values Grid -->
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4 glow-effect">Our Values and Commitment</h2>
            <p class="text-lg text-gray-400 mb-12">
                Discover the principles that drive us to deliver exceptional services and ensure your success.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Integrity -->
                <div class="bg-gray-800 p-8 rounded-lg shadow-lg" data-aos="flip-left">
                    <div
                        class="flex items-center justify-center w-16 h-16 bg-red-600 text-white rounded-full mx-auto mb-6">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-white mb-4">Integrity</h4>
                    <p class="text-gray-400 leading-relaxed">
                        We uphold the highest standards of integrity in every aspect of our work, ensuring our clients
                        receive honest, transparent, and dependable service.
                    </p>
                </div>
                <!-- Security -->
                <div class="bg-gray-800 p-8 rounded-lg shadow-lg" data-aos="flip-up">
                    <div
                        class="flex items-center justify-center w-16 h-16 bg-red-600 text-white rounded-full mx-auto mb-6">
                        <i class="fas fa-shield-alt text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-white mb-4">Security</h4>
                    <p class="text-gray-400 leading-relaxed">
                        Our clients' security is our priority. Every account we deliver is verified, reliable, and
                        protected
                        by the latest standards.
                    </p>
                </div>
                <!-- Customer Focus -->
                <div class="bg-gray-800 p-8 rounded-lg shadow-lg" data-aos="flip-right">
                    <div
                        class="flex items-center justify-center w-16 h-16 bg-red-600 text-white rounded-full mx-auto mb-6">
                        <i class="fas fa-heart text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold text-white mb-4">Customer Focus</h4>
                    <p class="text-gray-400 leading-relaxed">
                        We are dedicated to our clients' success. Your growth is our growth, and we go the extra mile to
                        provide exceptional support and tailored solutions.
                    </p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center" data-aos="zoom-in">
            <h2 class="text-5xl text-white font-extrabold mb-6">Boost Your Business Today</h2>
            <p class="text-lg md:text-xl mb-12">
                Get premium social media accounts in bulk, verified and ready to elevate your digital presence.
            </p>
            <a href="{{ route('home') }}"
                class="bg-red-500 text-white py-4 px-10 rounded-full font-bold shadow-lg transition-transform transform hover:scale-110">
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
