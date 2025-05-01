@extends('layouts.app')

@section('title', 'Saved posts')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Saved posts</h1>

            @if (Auth::check() && Auth::id() == $user->id)
                <a href="{{ route('users.show', $user->id) }}" class="text-red-600 hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Profile
                </a>
            @endif
        </div>

        <div class="masonry-grid">
            @forelse($savedPosts as $savedPost)
                <div class="masonry-item">
                    <div
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <a href="{{ route('posts.show', $savedPost->post->id) }}" class="block">
                            @if ($savedPost->post->mediaContent->isNotEmpty())
                                <img src="{{ asset('storage/' . $savedPost->post->mediaContent->first()->path) }}"
                                    alt="{{ $savedPost->post->title }}" class="w-full object-cover"
                                    style="aspect-ratio: {{ rand(3, 5) }}/{{ rand(4, 8) }};">
                            @else
                                <div class="bg-gray-200 w-full"
                                    style="aspect-ratio: {{ rand(3, 5) }}/{{ rand(4, 8) }};"></div>
                            @endif
                        </a>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg truncate">{{ $savedPost->post->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 mt-1">{{ $savedPost->post->description }}</p>

                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center">
                                    <img src="{{ $savedPost->post->user->avatar ?? 'https://via.placeholder.com/40' }}"
                                        alt="User" class="w-8 h-8 rounded-full mr-2">
                                    <span class="text-sm font-medium">{{ $savedPost->post->user->username }}</span>
                                </div>

                                <div class="flex space-x-2">
                                    @if (Auth::check() && Auth::id() == $user->id)
                                        <form action="{{ route('users.saved-posts', $user->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="post_id" value="{{ $savedPost->post->id }}">
                                            <button type="submit"
                                                class="text-gray-600 hover:text-red-600 p-1 rounded-full hover:bg-gray-100">
                                                <i class="fas fa-bookmark"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <div class="text-gray-500 mb-4">
                        <i class="fas fa-bookmark text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">No saved posts yet</h3>
                    <p class="text-gray-600 mb-4">Save posts to find them later</p>
                    <a href="{{ route('posts.index') }}"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-full">
                        Discover ideas
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
