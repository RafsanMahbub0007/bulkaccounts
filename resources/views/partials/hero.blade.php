<!-- ✅ Enhanced Hero Banner -->
<section
    class="relative text-white overflow-hidden px-6 py-24"
    style="
        background-image:
            linear-gradient(rgba(76, 58, 90, 0.75), rgba(163, 70, 233, 0.75)),
            url('/img/pva-pro-banner.png');
        background-size: cover;
        background-position: left;
        background-repeat: no-repeat;
    "
>

    <!-- Main Container -->
    <div class="container mx-auto flex flex-col items-center text-center">

        <!-- Heading -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-red-500 leading-tight mb-6 drop-shadow-xl animate-fade-in">
            Buy Bulk Social Media Accounts Instantly
        </h1>

        <!-- Subtext -->
        <p class="text-base sm:text-lg md:text-xl text-gray-200 leading-relaxed max-w-2xl mb-8 animate-slide-in">
            High-quality verified accounts for Facebook, Instagram, Gmail, and more.
            Designed for businesses, marketers, and entrepreneurs.
        </p>

        <!-- Search Box -->
        <div class="w-full max-w-xl mb-10 animate-fade-in-delay">
            <form action="/search" method="GET" class="relative flex items-center">
                <input
                    type="text"
                    name="query"
                    placeholder="Search for accounts (e.g., Facebook, Instagram)..."
                    class="flex-1 px-5 py-4 rounded-full text-gray-700 placeholder-gray-500
                           shadow-lg focus:outline-none transition focus:ring-2 focus:ring-red-500"
                />
                <button
                    type="submit"
                    class="ml-3 bg-red-600 text-white px-6 py-3 rounded-full font-semibold
                           shadow-lg hover:bg-red-700 hover:scale-105 transition"
                >Search</button>
            </form>
        </div>

        <!-- CTAs -->
        <div class="flex flex-col sm:flex-row sm:space-x-6 space-y-4 sm:space-y-0 animate-slide-in-delay">
            <a
                href="{{ route('pricing') }}"
                class="bg-red-600 text-white px-10 py-4 rounded-lg font-bold shadow-xl
                       hover:bg-red-700 transition transform hover:scale-105 text-center"
            >
                Shop Now
            </a>

            <a
                href="{{ route('contact') }}"
                class="bg-gray-700 text-white px-10 py-4 rounded-lg font-bold shadow-xl
                       hover:bg-gray-800 transition transform hover:scale-105 text-center"
            >
                Contact Us
            </a>
        </div>
    </div>


    <!-- Stats Cards -->
    <div class="container mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 py-16 mt-48">

        <!-- Card Template -->
        <div class="bg-gray-800 shadow-xl rounded-lg p-8 text-center
                    transform hover:scale-105 transition duration-300 hover:shadow-red-600/20">
            <div class="flex justify-center mb-6">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-check text-red-500 text-2xl"></i>
                </div>
            </div>
            <div class="text-white text-4xl font-bold mb-1">10K+</div>
            <p class="text-gray-400 text-lg">Accounts Sold</p>
        </div>

        <div class="bg-gray-800 shadow-xl rounded-lg p-8 text-center
                    transform hover:scale-105 transition duration-300 hover:shadow-red-600/20">
            <div class="flex justify-center mb-6">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-alt text-red-500 text-2xl"></i>
                </div>
            </div>
            <div class="text-white text-4xl font-bold mb-1">5+</div>
            <p class="text-gray-400 text-lg">Years of Experience</p>
        </div>

        <div class="bg-gray-800 shadow-xl rounded-lg p-8 text-center
                    transform hover:scale-105 transition duration-300 hover:shadow-red-600/20">
            <div class="flex justify-center mb-6">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-smile text-red-500 text-2xl"></i>
                </div>
            </div>
            <div class="text-white text-4xl font-bold mb-1">99%</div>
            <p class="text-gray-400 text-lg">Customer Satisfaction</p>
        </div>

        <div class="bg-gray-800 shadow-xl rounded-lg p-8 text-center
                    transform hover:scale-105 transition duration-300 hover:shadow-red-600/20">
            <div class="flex justify-center mb-6">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-headset text-red-500 text-2xl"></i>
                </div>
            </div>
            <div class="text-white text-4xl font-bold mb-1">24/7</div>
            <p class="text-gray-400 text-lg">Customer Support</p>
        </div>

    </div>

    <!-- Decorative Glow Balls -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-red-600 opacity-20 blur-[100px] rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-700 opacity-20 blur-[120px] rounded-full"></div>

</section>
