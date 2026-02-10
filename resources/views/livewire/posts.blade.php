<div class="bg-gray-900 min-h-screen py-8">
    @php
        $seo = \App\Models\SeoSetting::where('page_name', 'blog')->first();
        $title = $seo->meta_title ?? 'Blog - Latest Insights & Updates';
        $description = $seo->meta_description ?? 'Explore insights, tips, and updates on the latest trends in account selling and digital growth.';
        $keywords = $seo->meta_keywords ?? '';
    @endphp
    @section('title', $title)
    @section('description', $description)
    @section('keywords', $keywords)

    <!-- Hero Section -->
    <section class="py-16 bg-cover bg-center relative" style="background-image: url('path-to-your-hero-image.jpg');">
        <div class="absolute inset-0 bg-gray-900 bg-opacity-75"></div>
        <div class="container mx-auto px-4 lg:px-8 text-center relative z-10">
            <h1 class="text-5xl font-extrabold text-white mb-4">
                Welcome to Our Blog
            </h1>
            <p class="text-lg text-gray-300 mb-6">
                Explore insights, tips, and updates on the latest trends in account selling and digital growth.
            </p>
            <a href="#posts"
                class="bg-red-600 text-white py-3 px-8 rounded-full font-bold shadow-lg transition-transform transform hover:scale-110">
                Start Reading
            </a>
        </div>
    </section>

    <!-- Blog Section -->
    <div class="container mx-auto px-4 lg:px-8 mt-8" id="posts">
        <div class="flex justify-between">
            <h1 class="text-4xl font-extrabold text-white mb-6">Blog Posts</h1>

            <!-- Search Bar -->
            <div class="mb-6">
                <x-input type="text" wire:model.live.debounce.300ms="search" placeholder="Search posts..." />
            </div>
        </div>

        <!-- Post List -->
        @if ($posts->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                    <div
                        class="bg-gray-800 p-6 rounded-lg shadow-lg hover:bg-gray-700 transition duration-300 ease-in-out">
                        <!-- Post Image -->
                        <img src="{{image_path( $post->image) }}" alt="{{ $post->title }}"
                            class="w-full h-56 object-cover rounded-t-lg mb-6">

                        <!-- Post Title -->
                        <h2 class="text-3xl font-semibold text-white mb-4">
                            <a href="{{ route('post.show', $post->slug) }}"
                                class="hover:text-red-600 transition duration-300">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <!-- Post Description (optional) -->
                        @if ($post->description)
                            <p class="text-lg text-gray-300 mb-6">{{ Str::limit($post->description, 100) }}</p>
                        @endif

                        <!-- Post Meta Information -->
                        <div class="flex items-center space-x-4 text-sm text-gray-400 mb-4">
                            <span class="flex items-center">
                                <i class="fas fa-user mr-1"></i> {{ $post->author->name }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ $post->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        <!-- Read More Link -->
                        <a href="{{ route('post.show', $post->slug) }}"
                            class="text-red-600 font-medium hover:underline transition duration-300 ease-in-out">
                            Read More
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{-- Pagination removed --}}
            </div>
        @else
            <p class="text-gray-400 text-center">No posts found.</p>
        @endif
    </div>
</div>
