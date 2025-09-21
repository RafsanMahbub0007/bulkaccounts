<x-app-layout>
    <!-- Hero Section -->
    <section class="relative py-20 bg-gray-800 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-5xl font-extrabold text-white mb-4">
                Frequently Asked Questions
            </h1>
            <p class="text-lg md:text-xl text-gray-400 leading-relaxed max-w-2xl mx-auto">
                Find answers to the most common questions about our services and how we can help you achieve your goals.
            </p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center text-white mb-12">
                Your Questions, Answered
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                <!-- FAQ Item -->
                @foreach ([
        ['icon' => 'fa-question-circle', 'bgColor' => 'bg-red-500', 'title' => 'What kind of accounts do you offer?', 'text' => 'We offer verified bulk accounts for platforms like Facebook, Instagram, Twitter, and more. All accounts are ready for immediate use.'],
        ['icon' => 'fa-shield-alt', 'bgColor' => 'bg-red-500', 'title' => 'Are the accounts safe and verified?', 'text' => 'Yes, every account is manually verified to meet strict security standards, ensuring safety and reliability.'],
        ['icon' => 'fa-shopping-cart', 'bgColor' => 'bg-red-500', 'title' => 'How do I purchase accounts?', 'text' => 'Simply browse our product page, select your desired account type, and proceed with secure checkout. It’s fast and easy!'],
        ['icon' => 'fa-life-ring', 'bgColor' => 'bg-red-500', 'title' => 'What happens if I encounter issues?', 'text' => 'Our support team is here to assist you promptly with any issues. Contact us anytime for help.'],
        ['icon' => 'fa-undo', 'bgColor' => 'bg-red-500', 'title' => 'Can I request a refund if I’m not satisfied?', 'text' => 'Yes, we have a transparent refund policy. Please review the terms on our Refund Policy page.'],
        ['icon' => 'fa-headset', 'bgColor' => 'bg-red-500', 'title' => 'How can I contact customer support?', 'text' => 'Reach out via our Contact page or email us at support@yourwebsite.com. We’re happy to help.'],
    ] as $faq)
                    <div class="bg-gray-800 p-8 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-12 h-12 flex items-center justify-center flex-shrink-0 {{ $faq['bgColor'] }} text-white rounded-full shadow-lg hover:scale-110 transition">
                                <i class="fas {{ $faq['icon'] }} text-lg"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white ml-4">
                                {{ $faq['title'] }}
                            </h3>
                        </div>
                        <p class="text-gray-400 leading-relaxed">
                            {{ $faq['text'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-16 bg-gray-900 text-white text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold mb-6">Still Have Questions?</h2>
            <p class="text-lg md:text-xl mb-8">
                Can’t find the answer you’re looking for? Contact us, and we’ll be happy to help.
            </p>
            <a href="/contact"
                class="bg-red-500 text-white py-3 px-8 rounded-full font-bold shadow-lg hover:bg-red-600 transition-transform transform hover:scale-110">
                Contact Us
            </a>
        </div>
    </section>
</x-app-layout>
