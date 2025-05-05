@props(['relatedPosts' => null])

<div class="mt-12">
    <h2 class="text-xl font-bold mb-6">More like this</h2>

    <div class="masonry-grid">
        @if ($relatedPosts)
            @foreach ($relatedPosts as $relatedPost)
                <div class="masonry-item">
                    <div
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <a href="{{ route('posts.show', $relatedPost->id) }}" class="block">
                            @if ($relatedPost->mediaContent->first())
                                <img src="{{ asset('storage/' . $relatedPost->mediaContent->first()->path) }}"
                                    alt="{{ $relatedPost->title }}" class="w-full">
                            @else
                                <div class="bg-gray-200 w-full" style="aspect-ratio: 4/3;"></div>
                            @endif
                        </a>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg truncate">{{ $relatedPost->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 mt-1">{{ $relatedPost->description }}</p>

                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center">
                                    <img src="{{ $relatedPost->user->avatar ?? 'https://placehold.co/40' }}"
                                        alt="{{ $relatedPost->user->username }}" class="w-8 h-8 rounded-full mr-2">
                                    <span class="text-sm font-medium">{{ $relatedPost->user->username }}</span>
                                </div>

                                <div class="flex space-x-2">
                                    <button class="text-gray-600 hover:text-red-600 p-1 rounded-full hover:bg-gray-100">
                                        <i class="far fa-bookmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            @for ($i = 0; $i < 8; $i++)
                <div class="masonry-item">
                    <div
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <a href="#" class="block">
                            <div class="bg-gray-200 w-full"
                                style="aspect-ratio: {{ rand(3, 5) }}/{{ rand(4, 8) }};"></div>
                        </a>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg truncate">Related post Title</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 mt-1">Description of the related post</p>

                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center">
                                    <img src="https://placehold.co/40" alt="User" class="w-8 h-8 rounded-full mr-2">
                                    <span class="text-sm font-medium">Username</span>
                                </div>

                                <div class="flex space-x-2">
                                    <button class="text-gray-600 hover:text-red-600 p-1 rounded-full hover:bg-gray-100">
                                        <i class="far fa-bookmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        @endif
    </div>
</div>
