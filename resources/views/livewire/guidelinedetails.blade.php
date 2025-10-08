<section class="relative py-20 bg-gray-900 text-white">
    <div class="container mx-auto px-4">
        <!-- Hero / Header -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-white mb-4">
                User Guideline
            </h1>
            <p class="text-lg md:text-xl text-gray-400 leading-relaxed max-w-2xl mx-auto">
                Simple rules to help you get the most out of your experience.
            </p>
        </div>

        <!-- GuideLines Section -->
        <div class="mb-16">
            <h2 class="text-4xl font-bold text-center text-white mb-12">
                Lets Jump!!
            </h2>
            <div class="space-y-6">
                @foreach ($guidlines as $guidline)
                    <div x-data="{ open: false }" class="bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-all">
                        <!-- Title row -->
                        <div class="flex justify-between items-center p-6 cursor-pointer hover:bg-gray-700 transition"
                            @click="open = !open">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 flex items-center justify-center bg-red-500 text-white rounded-full shadow-md">
                                    <i class="fas fa-question-circle"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-white">{{ $guidline->title }}</h3>
                            </div>

                            <!-- Plus / Minus Icon -->
                            <div>
                                <i class="fas" :class="open ? 'fa-minus text-red-400' : 'fa-plus text-gray-400'"></i>
                            </div>
                        </div>

                        <!-- Hidden Content -->
                        <div x-show="open" x-collapse class="p-6 border-t border-gray-700 space-y-4">
                            

                            @php
                                preg_match('/(?:youtu\.be\/|v=)([a-zA-Z0-9_-]+)/', $guidline->youtube_link, $matches);
                                $videoId = $matches[1] ?? null;
                            @endphp

                            @if ($videoId)
                                <div class="aspect-video rounded-lg overflow-hidden shadow-md">
                                    <iframe class="w-full h-full"
                                        src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            @else
                                <p class="text-gray-500 italic">Invalid YouTube link</p>
                            @endif
                            <div class="text-gray-400 leading-relaxed">
                                {!! $guidline->details !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
</section>
