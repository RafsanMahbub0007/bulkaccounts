<div class="bg-gray-900 text-gray-300 min-h-screen py-12">

    <!-- Post Detail Section -->
    <div class="container mx-auto px-6 lg:px-16 py-8">
        <!-- Post Header with Title and Meta Information -->
        <div class="mb-8">
            <!-- Title -->
            <h1 class="text-4xl font-bold text-red-500 mb-4">{{ $post->title }}</h1>

            <!-- Meta Information -->
            <div class="flex items-center text-sm text-gray-400 space-x-4 mb-6">
                <span class="flex items-center">
                    <i class="fas fa-user mr-1"></i> {{ $post->author->name }}
                </span>
                <span class="flex items-center">
                    <i class="fas fa-calendar-alt mr-1"></i> {{ $post->created_at->format('M d, Y') }}
                </span>
                @if ($post->categories)
                    <span class="flex items-center">
                        <i class="fas fa-tags mr-1"></i>
                        @foreach ($post->categories as $category)
                            <span class="mr-2">{{ $category->name }}</span>
                        @endforeach
                    </span>
                @endif
            </div>
        </div>

        <!-- Post Image -->
        <div class="mb-8">
            @if ($post->image)
                <img src="{{ asset('/storage/' . $post->image) }}" alt="{{ $post->title }}"
                    class="mx-auto object-contain max-w-full h-auto">
            @endif
        </div>

        <!-- Post Content -->
        <div class="prose prose-red max-w-none mb-8">
            {!! $post->content !!}
        </div>

        <!-- Related Posts Section -->
        <div class="bg-gray-700 py-6 px-8 mt-6">
            <h2 class="text-2xl font-bold text-gray-200 mb-4">Related Posts</h2>
            <div class="flex flex-wrap -mx-4">
                @foreach ($relatedPosts as $relatedPost)
                    <a href="{{ route('blog.post', $relatedPost->slug) }}" class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-4">
                        <div class="bg-gray-800 p-4 rounded-lg hover:bg-gray-700 transition">
                            <h3 class="text-lg font-semibold text-gray-200 truncate">{{ $relatedPost->title }}</h3>
                            <p class="text-sm text-gray-400 truncate">{{ $relatedPost->description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Footer (from Bulk Account Seller theme) -->
    <footer>
        <!-- Include your existing footer here -->
    </footer>
</div>
