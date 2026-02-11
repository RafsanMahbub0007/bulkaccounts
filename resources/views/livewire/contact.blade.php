<div>
    @php
        $seo = \App\Models\SeoSetting::where('page_name', 'contact')->first();
        $title = $seo->meta_title ?? 'Contact Us - Get in Touch';
        $description = $seo->meta_description ?? 'Have questions or need support? Contact our team for assistance with bulk accounts and digital products.';
        $keywords = $seo->meta_keywords ?? '';
    @endphp
    @section('title', $title)
    @section('description', $description)
    @section('keywords', $keywords)

    <!-- Contact Banner -->
    <section class="bg-gray-900 py-28 relative overflow-hidden">
        <div class="container mx-auto px-6 text-center relative z-10">
            <!-- Heading -->
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-4 animate-fade-in">
                We’re Here to Help
            </h2>
            <!-- Subtext -->
            <p class="text-lg md:text-xl text-gray-300 mb-6 animate-slide-in">
                Reach out to us for any queries or feedback. We’ll respond as soon as possible.
            </p>
        </div>
    </section>

@php
    $system =App\Models\Setting::find(1);
@endphp
    <section class="bg-gradient-to-br from-gray-900 to-gray-800 py-28">
        <!-- Main Contact Section -->
        <div class="container mx-auto px-4 mt-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div class="bg-gray-800 rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-white mb-6">Contact Information</h3>
                    <ul class="space-y-6">
                        <li class="flex items-center">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-red-500 to-red-300 text-white rounded-full shadow-lg">
                                <i class="fas fa-phone-alt text-lg"></i>
                            </div>
                            <span class="ml-4 text-gray-300 text-lg">{{ $system->phone }}</span>
                        </li>
                        <li class="flex items-center">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-green-500 to-green-300 text-white rounded-full shadow-lg">
                                <i class="fas fa-envelope text-lg"></i>
                            </div>
                            <span class="ml-4 text-gray-300 text-lg">{{ $system->email }}</span>
                        </li>
                        <li class="flex items-center">
                            <div
                                class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-red-500 to-red-300 text-white rounded-full shadow-lg">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>
                            <span class="ml-4 text-gray-300 text-lg">{{ $system->address }}</span>
                        </li>
                    </ul>

                </div>

                <!-- Contact Form -->
                <div class="bg-gray-800 rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-white mb-6">Send Us a Message</h3>
                    
                    @if (session()->has('message'))
                        <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="submit">
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-gray-300 mb-2">Your Name</label>
                                <input type="text" id="name" wire:model="name"
                                    class="w-full p-4 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-red-500 @error('name') border-red-500 @enderror"
                                    placeholder="Enter your name">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-gray-300 mb-2">Your Email</label>
                                <input type="email" id="email" wire:model="email"
                                    class="w-full p-4 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-red-500 @error('email') border-red-500 @enderror"
                                    placeholder="Enter your email">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="message" class="block text-gray-300 mb-2">Your Message</label>
                                <textarea id="message" wire:model="message" rows="6"
                                    class="w-full p-4 rounded-lg border border-gray-600 bg-gray-700 text-white focus:ring-2 focus:ring-red-500 @error('message') border-red-500 @enderror"
                                    placeholder="Write your message here..."></textarea>
                                @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full bg-red-500 text-white py-3 px-6 rounded-lg mt-6 hover:bg-red-400 transition flex justify-center items-center"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Send Message</span>
                            <span wire:loading><i class="fas fa-spinner fa-spin"></i> Sending...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="mt-16">
            <div class="container mx-auto px-4">
                <h3 class="text-2xl font-bold text-white text-center mb-6">Visit Us</h3>
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden shadow-lg">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.835434508617!2d144.9537353153175!3d-37.81627944202185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf57766b8c9c902c!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1610336447340!5m2!1sen!2sau"
                        style="border:0; width: 100%; height: 100%; min-height: 500px;" allowfullscreen=""
                        loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>

</div>
