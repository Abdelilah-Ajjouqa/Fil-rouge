@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Discover Ideas</h1>
        @auth
            <a href="{{ route('posts.create') }}"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-full flex items-center">
                <i class="fas fa-plus mr-2"></i> Create Pin
            </a>
        @endauth
    </div>

    <div class="masonry-grid">
        @forelse($post as $item)
            <div class="masonry-item">
                <div
                    class="bg-gray-50 rounded-lg shadow-md overflow-hidden hover:shadow-xl hover:opacity-85 transition-shadow duration-300">
                    <a href="{{ route('posts.show', $item->id) }}" class="block">
                        @if ($item->mediaContent->isNotEmpty())
                            <img src="{{ asset('storage/' . $item->mediaContent->first()->path) }}"
                                alt="{{ $item->title }}" class="w-full object-cover">
                        @else
                            <div class="bg-gray-200 w-full" style="aspect-ratio: {{ rand(3, 5) }}/{{ rand(4, 8) }};">
                            </div>
                        @endif
                    </a>
                    <div class="p-2">
                        <h3 class="font-semibold text-lg truncate cursor-pointer">{{ $item->title }}</h3>
                        <p class="text-gray-600 text-sm line-clamp-2 mt-0.5 cursor-pointer">{{ $item->description }}</p>

                        <div class="flex items-center justify-between mt-2">
                            <a href="{{ route('users.show', $item->user_id) }}"
                                class="flex items-center duration-300 hover:drop-shadow-2xl hover:text-red-500">
                                <img src="{{ $item->user->avatar ?? 'https://placehold.co/40' }}" alt="User"
                                    class="w-8 h-8 rounded-full mr-2">
                                <span class="text-sm font-medium">{{ $item->user->username }}</span>
                            </a>

                            <div class="flex gap-x-1">
                                @auth
                                    @php
                                        // Check if the post is saved by the current user
                                        $isSaved = \App\Models\SavedPost::where('user_id', Auth::id())
                                            ->where('post_id', $item->id)
                                            ->exists();
                                    @endphp
                                    <button
                                        class="unsave-btn text-gray-600 hover:text-red-600 p-1 rounded-full hover:bg-gray-100"
                                        data-post-id="{{ $item->id }}">
                                        <i class="{{ $isSaved ? 'fas' : 'far' }} fa-bookmark"></i>
                                    </button>
                                    <button class="text-gray-600 hover:text-red-600 p-1 rounded-full hover:bg-gray-100">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <div class="text-gray-500 mb-4">
                    <i class="fas fa-image text-5xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">No pins found</h3>
                <p class="text-gray-600">Be the first to create a pin!</p>
                @auth
                    <a href="{{ route('posts.create') }}"
                        class="mt-4 inline-block bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-full">
                        Create Pin
                    </a>
                @else
                    <a href="{{ route('auth.login.form') }}"
                        class="mt-4 inline-block bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-full">
                        Log in to create pins
                    </a>
                @endauth
            </div>
        @endforelse
    </div>
@endsection

@section('scripts')
    @include('components.save')
@endsection
