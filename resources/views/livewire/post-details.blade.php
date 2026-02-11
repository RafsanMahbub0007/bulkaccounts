<div class="bg-gray-900 text-gray-300 min-h-screen py-12">
    @section('title', $post->meta_title ?? $post->title . ' - Blog')
    @section('description', $post->description ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 150))
    @section('keywords', $post->keywords ?? '')

    <div class="container mx-auto px-6 lg:px-16 flex flex-col lg:flex-row gap-12">

        <!-- Main Content (Left) -->
        <div class="lg:w-2/3 space-y-8">

            <!-- Post Header -->
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-red-500 mb-4">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400">
                    <span class="flex items-center gap-1">
                        <i class="fas fa-user"></i> {{ $post->author->name }}
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="fas fa-calendar-alt"></i> {{ $post->created_at->format('M d, Y') }}
                    </span>
                    @if ($post->categories)
                        <span class="flex items-center gap-1">
                            <i class="fas fa-tags"></i>
                            @foreach ($post->categories as $category)
                                <span class="bg-gray-700 px-2 py-0.5 rounded text-gray-300 text-xs">{{ $category->name }}</span>
                            @endforeach
                        </span>
                    @endif
                </div>
            </div>

            <!-- Post Image -->
            @if ($post->image)
            <div class="text-center">
                <img src="{{ image_path($post->image) }}" alt="{{ $post->title }}" class="mx-auto rounded-lg shadow-lg max-w-full h-auto">
            </div>
            @endif

            <!-- Post Content -->
            <div class="prose prose-red max-w-none">
                {!! $post->content !!}
            </div>

        </div>

        <!-- Sidebar (Right) -->
        <div class="lg:w-1/3 space-y-6">
            <div class="bg-gray-800 p-6 rounded-2xl shadow-lg sticky top-24">
                <h2 class="text-2xl font-bold text-gray-200 mb-4">Related Posts</h2>
                <div class="space-y-4">
                    @foreach ($relatedPosts as $relatedPost)
                        <a href="{{ route('post.show', $relatedPost->slug) }}">
                            <div class="bg-gray-700 p-4 rounded-lg hover:bg-gray-600 transition-shadow duration-300 shadow-md">
                                <h3 class="text-lg font-semibold text-gray-200 truncate">{{ $relatedPost->title }}</h3>
                                <p class="text-sm text-gray-400 line-clamp-2">{{ $relatedPost->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-700 py-8 mt-12">
        <!-- Include your existing footer here -->
    </footer>

</div>
