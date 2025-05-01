@extends('layouts.app')

@section('title', 'Archived Pins')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Archived Pins</h1>
            <a href="{{ route('admin.dashboard') }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center">
                    <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                        <i class="fas fa-archive"></i>
                    </div>
                    <h2 class="text-xl font-semibold">Archived Pins ({{ count($posts) }})</h2>
                </div>
                <div class="relative">
                    <input type="text" id="postSearch" placeholder="Search pins..."
                        class="border rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts as $post)
                    <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="relative pb-2/3">
                            @if ($post->mediaContent->count() > 0)
                                <img src="{{ asset('storage/' . $post->mediaContent->first()->path) }}"
                                    alt="{{ $post->title }}" class="absolute h-full w-full object-cover">
                            @else
                                <div class="absolute h-full w-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Archived</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium text-gray-900 mb-1 truncate">{{ $post->title }}</h3>
                            <p class="text-gray-500 text-sm mb-2 line-clamp-2">{{ $post->description ?? 'No description' }}
                            </p>
                            <div class="flex items-center text-xs text-gray-500 mb-3">
                                <i class="fas fa-user mr-1"></i>
                                <span>{{ $post->user->username }}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar mr-1"></i>
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex space-x-2">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-comment mr-1"></i>
                                        {{ $post->comments->count() }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-bookmark mr-1"></i>
                                        {{ $post->savedPosts->count() }}
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('posts.show', $post->id) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.posts.restore', $post->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="text-green-600 hover:text-green-900"
                                            onclick="return confirm('Are you sure you want to restore this pin?')">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to permanently delete this pin? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-8 text-center text-gray-500">
                        <i class="fas fa-archive text-4xl mb-3"></i>
                        <p>No archived pins found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .pb-2\/3 {
            padding-bottom: 66.666667%;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        document.getElementById('postSearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const postCards = document.querySelectorAll('.grid > div');

            postCards.forEach(card => {
                if (card.classList.contains('col-span-3')) return; // Skip the "No posts found" message

                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                const username = card.querySelector('.fas.fa-user').nextSibling.textContent.toLowerCase();

                if (title.includes(searchValue) || description.includes(searchValue) || username.includes(
                        searchValue)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
@endsection
