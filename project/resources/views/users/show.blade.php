@extends('layouts.app')

@section('title', $user->username)

@section('content')
    <div class="max-w-5xl mx-auto">
        <!-- Cover Image -->
        <div class="relative h-64 rounded-lg mb-6">
            @if ($user->cover)
                <img src="{{ asset($user->cover) }}" alt="Cover" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-r from-red-500 to-pink-500"></div>
            @endif

            <!-- Profile Image -->
            <div class="absolute left-1/2 bottom-0 transform -translate-x-1/2 translate-y-1/2">
                <div class="w-32 h-32 rounded-full border-4 border-white overflow-hidden bg-white">
                    @if ($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->username }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="text-center mt-16 mb-8">
            <h1 class="text-2xl font-bold">{{ $user->first_name }} {{ $user->last_name }}</h1>
            <span class="flex justify-center text-gray-600">
                <p>@</p>
                <p>{{ $user->username }}</p>
            </span>


            <div class="flex justify-center mt-4 space-x-2">
                @if (Auth::check() && Auth::id() == $user->id)
                    <a href="{{ route('users.edit', $user->id) }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-full">
                        <i class="fas fa-edit mr-1"></i> Edit Profile
                    </a>
                @else
                    <button class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-full">
                        Follow
                    </button>
                @endif

                <button class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-full">
                    <i class="fas fa-share-alt mr-1"></i> Share
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b mb-6">
            <div class="flex justify-center">
                <button id="created-tab" class="px-4 py-2 border-b-2 border-red-600 text-red-600 font-medium">
                    Created
                </button>
                <button id="saved-tab" class="px-4 py-2 border-b-2 border-transparent text-gray-600 hover:text-gray-800">
                    Saved
                </button>
            </div>
        </div>

        <!-- Created posts Grid -->
        <div id="created-posts" class="masonry-grid">
            @forelse($posts as $item)
                <div class="masonry-item">
                    <div
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <a href="{{ route('posts.show', $item->id) }}" class="block">
                            @if ($item->mediaContent->isNotEmpty())
                                <img src="{{ asset('storage/' . $item->mediaContent->first()->path) }}"
                                    alt="{{ $item->title }}" class="w-full object-cover">
                            @else
                                <div class="bg-gray-200 w-full"></div>
                            @endif
                        </a>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg truncate">{{ $item->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 mt-1">{{ $item->description }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <div class="text-gray-500 mb-4">
                        <i class="fas fa-image text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">No posts yet</h3>
                    @if (Auth::check() && Auth::id() == $user->id)
                        <p class="text-gray-600 mb-4">Share your ideas with the world!</p>
                        <a href="{{ route('posts.create') }}"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-full">
                            Create post
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Saved posts -->
        <div id="saved-posts" class="masonry-grid hidden">
            @forelse($savedPosts as $savedPost)
                <div class="masonry-item">
                    <div
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <a href="{{ route('posts.show', $savedPost->post->id) }}" class="block">
                            @if ($savedPost->post->mediaContent->isNotEmpty())
                                <img src="{{ asset('storage/' . $savedPost->post->mediaContent->first()->path) }}"
                                    alt="{{ $savedPost->post->title }}" class="w-full object-cover">
                            @else
                                <div class="bg-gray-200 w-full"></div>
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
                                        <!-- Replace the existing form with this button -->
                                        <button type="button"
                                            class="unsave-btn text-gray-600 hover:text-red-600 p-1 rounded-full hover:bg-gray-100"
                                            data-post-id="{{ $savedPost->post->id }}">
                                            <i class="fas fa-bookmark"></i>
                                        </button>
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

@section('scripts')
    @include('components.tabs')
    @include('components.save')
@endsection
