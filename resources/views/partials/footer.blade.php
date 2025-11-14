<footer class="bg-gradient-to-br from-gray-900 to-gray-800 text-white py-16 px-6">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
        <!-- Column 1: About Us -->
        <div>
            <h3 class="text-2xl font-bold mb-6 text-red-500">About Us</h3>
            <p class="text-gray-300 text-sm leading-relaxed">
                We deliver high-quality bulk social media accounts to help you scale your business. Trusted by thousands
                globally for reliability and affordability.
            </p>
            <div class="mt-6">
                <a href="/about"
                    class="inline-block bg-red-500 text-white py-2 px-6 rounded-full hover:bg-red-400 transition-all duration-300 shadow hover:shadow-lg">
                    Learn More
                </a>
            </div>
        </div>

        <!-- Column 2: Quick Links -->
        <div>
            <h3 class="text-2xl font-bold mb-6 text-red-500">Quick Links</h3>
            <ul class="space-y-4 text-gray-300">
                <li><a href="{{route('blog')}}" class="hover:text-red-400 transition-colors duration-300">Blog</a></li>
                <li><a href="{{route('about')}}" class="hover:text-red-400 transition-colors duration-300">About</a></li>
                <li><a href="{{route('contact')}}" class="hover:text-red-400 transition-colors duration-300">Contact</a></li>
                <li><a href="{{route('faq')}}" class="hover:text-red-400 transition-colors duration-300">FAQ</a></li>
                <li><a href="{{route('privacy')}}" class="hover:text-red-400 transition-colors duration-300">Privacy Policy</a></li>
                <li><a href="{{route('terms')}}" class="hover:text-red-400 transition-colors duration-300">Terms & Conditions</a></li>
            </ul>
        </div>

        <!-- Column 3: Categories -->
        @php
            $cats = \App\Models\Category::where('is_active',1)->orderBy('order','ASC')->get();
        @endphp
        <div>
            <h3 class="text-2xl font-bold mb-6 text-red-500">Categories</h3>
            <ul class="space-y-4 text-gray-300">
                @forelse ($cats as $cat)
                    <li>
                        <a href="{{route('category.details',$cat->slug)}}"
                           class="hover:text-red-400 transition-colors duration-300">
                           {{$cat->name}}
                        </a>
                    </li>
                @empty
                    <li><a href="#" class="hover:text-red-400 transition-colors duration-300">No Categories Yet</a></li>
                @endforelse
            </ul>
        </div>

        <!-- Column 4: Contact Us -->
        <div>
            <h3 class="text-2xl font-bold mb-6 text-red-500">Contact Us</h3>
            <ul class="space-y-6 text-sm">
                <li class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-700 text-white rounded-full">
                        <i class="fas fa-envelope text-sm"></i>
                    </div>
                    <a href="mailto:{{ $system->email }}" class="ml-3 hover:text-red-400 transition-colors duration-300">
                        {{ $system->email }}
                    </a>
                </li>
                <li class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-700 text-white rounded-full">
                        <i class="fas fa-phone-alt text-sm"></i>
                    </div>
                    <a href="tel:{{ $system->phone }}" class="ml-3 hover:text-red-400 transition-colors duration-300">
                        {{ $system->phone }}
                    </a>
                </li>
                <li class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-700 text-white rounded-full">
                        <i class="fas fa-map-marker-alt text-sm"></i>
                    </div>
                    <span class="ml-3 text-gray-300">{{ $system->address }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Social Media Section -->
    @php
        $socialLinks = [
            'facebook' => ['url' => $system->f_link, 'icon' => 'facebook-f', 'colors' => 'from-red-500 to-red-400'],
            'twitter' => ['url' => $system->tw_link, 'icon' => 'twitter', 'colors' => 'from-blue-700 to-blue-600'],
            'instagram' => ['url' => $system->i_link, 'icon' => 'instagram', 'colors' => 'from-pink-500 to-pink-400'],
            'telegram' => ['url' => $system->t_link, 'icon' => 'telegram', 'colors' => 'from-blue-500 to-blue-400'],
            'youtube' => ['url' => $system->y_link, 'icon' => 'youtube', 'colors' => 'from-red-500 to-red-400'],
            'linkedin' => ['url' => $system->lnkd_link, 'icon' => 'linkedin', 'colors' => 'from-blue-600 to-blue-800'],
        ];
    @endphp

    <div class="mt-12 text-center">
        <h3 class="text-2xl font-bold mb-4 text-red-500">Follow Us</h3>
        <div class="flex justify-center space-x-6">
            @foreach ($socialLinks as $platform)
                @if (!empty($platform['url']))
                    <a href="{{ $platform['url'] }}" target="_blank"
                       class="w-12 h-12 flex items-center justify-center bg-gradient-to-r {{ $platform['colors'] }} text-white rounded-full shadow-lg transform hover:scale-110 transition-all duration-300">
                        <i class="fab fa-{{ $platform['icon'] }} text-lg"></i>
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="mt-12 border-t border-gray-700 pt-6 text-center text-sm text-gray-400 flex flex-col sm:flex-row justify-center items-center gap-2 sm:gap-4">
        <span>&copy; {{ date('Y') }} YourWebsite. All rights reserved.</span>
        <span>|</span>
        <a href="{{route('privacy')}}" class="hover:text-red-400 transition-colors duration-300">Privacy Policy</a>
    </div>
</footer>
