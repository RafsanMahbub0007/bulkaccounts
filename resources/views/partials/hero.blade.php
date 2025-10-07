<section class="bg-gray-900 text-white relative overflow-hidden px-6 py-20">
    <!-- Main Container -->
    <div class="container mx-auto flex flex-col items-center text-center mb-20">
        <!-- Heading and Subtext -->
        <h1 class="text-5xl md:text-6xl font-extrabold text-red-500 leading-tight mb-6 animate-fade-in">
            Buy Bulk Social Media Accounts Instantly
        </h1>
        <p class="text-lg md:text-xl text-gray-300 leading-relaxed max-w-3xl mb-8 animate-slide-in">
            High-quality verified accounts for Facebook, Instagram, Gmail, and more. Designed for businesses, marketers,
            and entrepreneurs.
        </p>

        <!-- Search Box -->
        <div class="w-full max-w-2xl mb-10 animate-fade-in-delay">
            <form action="/search" method="GET" class="relative">
                <input type="text" name="query" placeholder="Search for accounts (e.g., Facebook, Instagram)..."
                    class="w-full px-6 py-4 rounded-full text-gray-700 placeholder-gray-500 shadow-lg focus:outline-none border-none transition focus:ring-2 focus:ring-red-600" />
                <button type="submit"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-red-600 text-white px-6 py-3 rounded-full font-semibold shadow-lg hover:bg-red-700 hover:scale-105 transition">
                    Search
                </button>
            </form>
        </div>

        <!-- Call-to-Action Buttons -->
        <div class="flex flex-wrap justify-center space-x-6 animate-slide-in-delay">
            <a href="{{ route('pricing') }}"
                class="bg-red-600 text-white px-10 py-5 rounded-lg font-bold shadow-lg hover:bg-red-700 transition transform hover:scale-105">
                Shop Now
            </a>
            <a href="{{ route('contact') }}"
                class="bg-gray-700 text-white px-10 py-5 rounded-lg font-bold shadow-lg hover:bg-gray-800 transition transform hover:scale-105">
                Contact Us
            </a>
        </div>
    </div>



    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 py-12">
        <!-- Card 1 -->
        <div
            class="bg-gray-800 shadow-md rounded-lg p-8 text-center transform hover:scale-105 transition-transform duration-300">
            <div class="flex justify-center items-center mb-6">
                <div class="bg-red-100 w-16 h-16 rounded-full flex justify-center items-center shadow-lg">
                    <i class="fas fa-user-check text-red-500 text-2xl"></i>
                </div>
            </div>
            <div class="text-white text-4xl font-bold mb-2">10K+</div>
            <p class="text-gray-400 text-lg">Accounts Sold</p>
        </div>

        <!-- Card 2 -->
        <div
            class="bg-gray-800 shadow-md rounded-lg p-8 text-center transform hover:scale-105 transition-transform duration-300">
            <div class="flex justify-center items-center mb-6">
                <div class="bg-red-100 w-16 h-16 rounded-full flex justify-center items-center shadow-lg">
                    <i class="fas fa-calendar-alt text-red-500 text-2xl"></i>
                </div>
            </div>
            <div class="text-white text-4xl font-bold mb-2">5+</div>
            <p class="text-gray-400 text-lg">Years of Experience</p>
        </div>

        <!-- Card 3 -->
        <div
            class="bg-gray-800 shadow-md rounded-lg p-8 text-center transform hover:scale-105 transition-transform duration-300">
            <div class="flex justify-center items-center mb-6">
                <div class="bg-red-100 w-16 h-16 rounded-full flex justify-center items-center shadow-lg">
                    <i class="fas fa-smile text-red-500 text-2xl"></i>
                </div>
            </div>
            <div class="text-white text-4xl font-bold mb-2">99%</div>
            <p class="text-gray-400 text-lg">Customer Satisfaction</p>
        </div>

        <!-- Card 4 -->
        <div
            class="bg-gray-800 shadow-md rounded-lg p-8 text-center transform hover:scale-105 transition-transform duration-300">
            <div class="flex justify-center items-center mb-6">
                <div class="bg-red-100 w-16 h-16 rounded-full flex justify-center items-center shadow-lg">
                    <i class="fas fa-headset text-red-500 text-2xl"></i>
                </div>
            </div>
            <div class="text-white text-4xl font-bold mb-2">24/7</div>
            <p class="text-gray-400 text-lg">Customer Support</p>
        </div>
    </div>



    <!-- Decorative Elements -->
    <div
        class="absolute top-0 left-0 w-80 h-80 bg-gradient-to-br from-gray-800 to-blue-900 rounded-full opacity-30 blur-3xl animate-pulse">
    </div>
    <div
        class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-gray-800 to-blue-900 rounded-full opacity-20 blur-3xl animate-pulse">
    </div>
</section>
