<section class="bg-gray-900 py-20">
    <div class="container mx-auto px-6 text-center">
        <!-- Section Title -->
        <h2 class="text-4xl sm:text-5xl font-serif text-white mb-6 tracking-wide">
            What Our Customers Say
        </h2>
        <!-- Section Subtitle -->
        <p class="text-lg sm:text-xl text-gray-300 mb-16 mx-auto max-w-3xl">
            Hear how our services have positively impacted businesses and individuals across industries.
        </p>

        <!-- Testimonial Cards -->
        <div class="flex flex-col md:flex-row justify-center gap-10 md:gap-16">
            <!-- Left Testimonial -->
            <div class="testimonial-card bg-white/10 backdrop-blur-lg p-6 sm:p-8 rounded-xl shadow-lg transition-transform duration-300 ease-in-out hover:scale-105">
                <div class="flex flex-col sm:flex-row items-center justify-center sm:space-x-6">
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Customer 1"
                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-white shadow-lg hover:shadow-2xl mb-4 sm:mb-0">
                    <div>
                        <h3 class="text-lg sm:text-xl font-serif font-semibold text-white">John Doe</h3>
                        <p class="text-sm sm:text-gray-400 text-gray-300">Marketing Manager</p>
                    </div>
                </div>
                <p class="text-gray-300 text-base sm:text-lg mt-6">
                    "The quality of the accounts was amazing, and the customer support is top-notch. Highly recommend!"
                </p>
            </div>

            <!-- Middle Testimonial (Focus) -->
            <div class="testimonial-card bg-white/10 backdrop-blur-lg p-8 sm:p-10 rounded-xl shadow-xl transform scale-105 transition-all duration-500 ease-in-out">
                <div class="flex flex-col sm:flex-row items-center justify-center sm:space-x-6">
                    <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Customer 2"
                        class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white shadow-lg hover:shadow-2xl mb-4 sm:mb-0">
                    <div>
                        <h3 class="text-lg sm:text-xl font-serif font-semibold text-white">Jane Smith</h3>
                        <p class="text-sm sm:text-gray-200 text-gray-300">Social Media Strategist</p>
                    </div>
                </div>
                <p class="text-white text-base sm:text-lg mt-6">
                    "This platform transformed my campaigns. Reliable, affordable, and with great support!"
                </p>
            </div>

            <!-- Right Testimonial -->
            <div class="testimonial-card bg-white/10 backdrop-blur-lg p-6 sm:p-8 rounded-xl shadow-lg transition-transform duration-300 ease-in-out hover:scale-105">
                <div class="flex flex-col sm:flex-row items-center justify-center sm:space-x-6">
                    <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="Customer 3"
                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-white shadow-lg hover:shadow-2xl mb-4 sm:mb-0">
                    <div>
                        <h3 class="text-lg sm:text-xl font-serif font-semibold text-white">Alex Johnson</h3>
                        <p class="text-sm sm:text-gray-400 text-gray-300">Business Owner</p>
                    </div>
                </div>
                <p class="text-gray-300 text-base sm:text-lg mt-6">
                    "The bulk accounts helped me scale my business in no time. Excellent service and support."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Additional Styling for Glassmorphism -->
<style>
    /* Glassmorphism Styles */
    .testimonial-card {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent background */
        border: 1px solid rgba(255, 255, 255, 0.2); /* Light border for contrast */
    }

    .testimonial-card:hover {
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        transform: scale(1.05);
    }

    /* Soft Glow Effect for Profile Images */
    .testimonial-card img {
        transition: all 0.3s ease;
    }

    .testimonial-card img:hover {
        box-shadow: 0 0 25px rgba(255, 255, 255, 0.8);
    }
</style>
