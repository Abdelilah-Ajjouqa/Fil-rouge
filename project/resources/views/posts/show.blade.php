@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row">
            <!-- Left side - Image -->
            <div class="md:w-3/5 bg-black flex items-center justify-center">
                @if ($post->mediaContent->isNotEmpty())
                    <img src="{{ asset('storage/' . $post->mediaContent->first()->path) }}" alt="{{ $post->title }}"
                        class="w-full h-auto max-h-[80vh] object-contain">
                @else
                    <div class="bg-gray-200 w-full h-96 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-5xl"></i>
                    </div>
                @endif
            </div>

            <!-- Right side - Content -->
            <div class="md:w-2/5 p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex space-x-2">
                        @auth
                            <form action="{{ route('users.saved-posts', Auth::id()) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-red-100 hover:bg-red-200 text-red-600 font-medium py-2 px-4 rounded-full">
                                    <i class="far fa-bookmark mr-1"></i> Save
                                </button>
                            </form>
                        @endauth
                    </div>

                    <div class="flex space-x-2">
                        <button class="text-gray-600 hover:bg-gray-100 p-2 rounded-full">
                            <i class="fas fa-share-alt"></i>
                        </button>

                        @if (Auth::check() && (Auth::id() == $post->user_id || Auth::user()->role == 'admin'))
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-600 hover:bg-gray-100 p-2 rounded-full">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                    <a href="{{ route('posts.edit', $post->id) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-edit mr-2"></i> Edit Pin
                                    </a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this pin?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            <i class="fas fa-trash-alt mr-2"></i> Delete Pin
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <h1 class="text-2xl font-bold mb-2">{{ $post->title }}</h1>

                @if ($post->description)
                    <p class="text-gray-700 mb-6">{{ $post->description }}</p>
                @endif

                <div class="flex items-center mb-6">
                    <a href="{{ route('users.show', $post->user_id) }}" class="flex items-center">
                        <img src="{{ $post->user->avatar ?? 'https://via.placeholder.com/40' }}" alt="User"
                            class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <h3 class="font-semibold">{{ $post->user->username }}</h3>
                            <p class="text-gray-600 text-sm">{{ $post->user->followers_count ?? 0 }} followers</p>
                        </div>
                    </a>

                    @if (Auth::check() && Auth::id() != $post->user_id)
                        <button
                            class="ml-auto bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-full">
                            Follow
                        </button>
                    @endif
                </div>

                @if ($post->tags->isNotEmpty())
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($post->tags as $tag)
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Comments Section -->
                <div>
                    <h3 class="font-semibold mb-4">Comments</h3>

                    @auth
                        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-6">
                            @csrf
                            <div class="flex">
                                <img src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/40' }}" alt="User"
                                    class="w-8 h-8 rounded-full mr-2">
                                <input type="text" name="content" placeholder="Add a comment"
                                    class="flex-grow border rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    required>
                                <button type="submit"
                                    class="ml-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="text-center mb-4">
                            <a href="{{ route('auth.login.form') }}" class="text-red-600 hover:underline">Log in</a> to add a
                            comment
                        </p>
                    @endauth

                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        @forelse($post->comments ?? [] as $comment)
                            <div class="flex">
                                <img src="{{ $comment->user->avatar ?? 'https://via.placeholder.com/40' }}" alt="User"
                                    class="w-8 h-8 rounded-full mr-2">
                                <div class="bg-gray-100 rounded-2xl px-4 py-2 flex-grow">
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-semibold text-sm">{{ $comment->user->username }}</h4>
                                        <span
                                            class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ $comment->content }}</p>
                                </div>

                                @if (Auth::check() && (Auth::id() == $comment->user_id || Auth::user()->role == 'admin'))
                                    <div class="relative ml-2" x-data="{ open: false }">
                                        <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false"
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                            <form action="{{ route('comments.destroy', [$post->id, $comment->id]) }}"
                                                method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-center text-gray-500">No comments yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Pins -->
    <div class="mt-12">
        <h2 class="text-xl font-bold mb-6">More like this</h2>

        <div class="masonry-grid">
            @for ($i = 0; $i < 8; $i++)
                <div class="masonry-item">
                    <div
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <a href="#" class="block">
                            <div class="bg-gray-200 w-full"
                                style="aspect-ratio: {{ rand(3, 5) }}/{{ rand(4, 8) }};"></div>
                        </a>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg truncate">Related Pin Title</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 mt-1">Description of the related pin</p>

                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center">
                                    <img src="https://via.placeholder.com/40" alt="User"
                                        class="w-8 h-8 rounded-full mr-2">
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
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // For image gallery if multiple images
        // document.addEventListener('DOMContentLoaded', function() {
        //     // Add any JavaScript for image gallery here
        // });
    </script>
@endsection
