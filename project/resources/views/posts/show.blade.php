@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="bg-white rounded-xl shadow-lg overflow-hidden max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row">
            <!-- Left side - Image with Pagination -->
            <div class="md:w-3/5 bg-black flex flex-col relative">
                <div class="flex-grow flex items-center justify-center">
                    @if ($post->mediaContent->isNotEmpty())
                        @foreach ($post->mediaContent as $index => $media)
                            @if (str_contains($media->type, 'video'))
                                <div id="media-{{ $index }}"
                                    class="media-item w-full h-full flex items-center justify-center {{ $index > 0 ? 'hidden' : '' }}">
                                    <video class="w-full h-auto max-h-[80vh] object-contain" controls loop playsinline
                                        preload="metadata">
                                        <source src="{{ asset('storage/' . $media->path) }}" type="{{ $media->type }}">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @else
                                <div id="media-{{ $index }}"
                                    class="media-item w-full h-full flex items-center justify-center {{ $index > 0 ? 'hidden' : '' }}">
                                    <img src="{{ asset('storage/' . $media->path) }}"
                                        alt="{{ $post->title }} - Image {{ $index + 1 }}"
                                        class="w-full h-auto max-h-[80vh] object-contain">
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="bg-gray-200 w-full h-96 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-5xl"></i>
                        </div>
                    @endif
                </div>

                @if ($post->mediaContent->count() > 1)
                    <!-- Pagination Controls -->
                    <div class="absolute inset-x-0 top-1/2 transform -translate-y-1/2 flex justify-between px-4 z-10">
                        <button id="prev-btn"
                            class="bg-white/80 hover:bg-white text-gray-800 rounded-full p-2 shadow-md transition-all">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button id="next-btn"
                            class="bg-white/80 hover:bg-white text-gray-800 rounded-full p-2 shadow-md transition-all">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <!-- Pagination Indicators -->
                    <div class="absolute bottom-4 inset-x-0 flex justify-center gap-2">
                        @foreach ($post->mediaContent as $index => $media)
                            <button
                                class="pagination-indicator h-2 w-2 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white/50' }}"
                                data-index="{{ $index }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right side - Content -->
            <div class="md:w-2/5 px-5 overflow-y-scroll overflow-x-hidden">
                <div class="w-[106%] flex justify-between items-center px-2 py-4 sticky top-0 bg-white">
                    <div class="flex space-x-2">
                        @auth
                            @php
                                // Check if the post is saved
                                $isSaved = \App\Models\SavedPost::where('user_id', Auth::id())
                                    ->where('post_id', $post->id)
                                    ->exists();
                            @endphp
                            <button type="button"
                                class="save-btn bg-red-100 hover:bg-red-200 text-red-600 font-medium py-2 px-4 rounded-full"
                                data-post-id="{{ $post->id }}" data-saved="{{ $isSaved ? 'true' : 'false' }}">
                                <i class="bookmark-icon {{ $isSaved ? 'fas' : 'far' }} fa-bookmark mr-1"></i>
                                <span class="bookmark-text">{{ $isSaved ? 'Saved' : 'Save' }}</span>
                            </button>
                        @endauth
                    </div>

                    <div class="flex space-x-3">
                        <button class="text-gray-600 hover:bg-gray-100 p-2 rounded-full">
                            <i class="fas fa-share-alt text-xl"></i>
                        </button>

                        @if (Auth::check() && Auth::id() == $post->user_id)
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-600 hover:bg-gray-100 p-2 rounded-full">
                                    <i class="fas fa-ellipsis-h text-xl"></i>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                    <a href="{{ route('posts.edit', $post->id) }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 ">
                                        <i class="fas fa-edit mr-2"></i> Edit post
                                    </a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                            <i class="fas fa-trash-alt mr-2"></i> Delete post
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if (Auth::check() && Auth::user()->role == 'admin')
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-600 hover:bg-gray-100 p-2 rounded-full">
                                    <i class="fas fa-ellipsis-h text-xl"></i>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                    <form action="{{ route('admin.posts.archive', $post->id) }}" method="post"
                                        onsubmit="return confirm('Are you sure you want to archive this post ?');">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-gray-100">
                                            <i class="fa-solid fa-box-archive"></i> Archive Post
                                        </button>
                                    </form>

                                </div>
                            </div>
                        @endif

                        @if (Auth::check() && Auth::id() == $post->user_id)
                            <button id="add-to-album-btn"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-full">
                                <i class="fas fa-folder-plus mr-1"></i> Add to Album
                            </button>

                            <!-- Add to Album Modal (hidden by default) -->
                            <div id="add-to-album-modal"
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                                    <div class="flex justify-between items-center border-b p-4">
                                        <h3 class="text-lg font-semibold">Add to Album</h3>
                                        <button id="close-album-modal" class="text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <div class="p-4" id="user-albums-container">
                                        <div class="flex justify-center items-center h-32">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                                        </div>
                                    </div>
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
                        <img src="{{ $post->user->avatar ?? 'https://placehold.co/40' }}" alt="User"
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
                <x-comments :post="$post" />
            </div>
        </div>
    </div>

    <!-- Related posts -->
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
        </div>
    </div>
@endsection

@section('scripts')
    @include('components.ajax');
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mediaItems = document.querySelectorAll('.media-item');
            const indicators = document.querySelectorAll('.pagination-indicator');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            let currentIndex = 0;
            const totalItems = mediaItems.length;

            if (totalItems <= 1) return;

            function showMedia(index) {
                mediaItems.forEach(item => item.classList.add('hidden'));

                mediaItems[index].classList.remove('hidden');

                // Update indicators
                indicators.forEach(indicator => indicator.classList.replace('bg-white', 'bg-white/50'));
                indicators[index].classList.replace('bg-white/50', 'bg-white');

                currentIndex = index;
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    const newIndex = (currentIndex + 1) % totalItems;
                    showMedia(newIndex);
                });
            }
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    const newIndex = (currentIndex - 1 + totalItems) % totalItems;
                    showMedia(newIndex);
                });
            }

            // Indicator clicks
            indicators.forEach(indicator => {
                indicator.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    showMedia(index);
                });
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight') {
                    const newIndex = (currentIndex + 1) % totalItems;
                    showMedia(newIndex);
                } else if (e.key === 'ArrowLeft') {
                    const newIndex = (currentIndex - 1 + totalItems) % totalItems;
                    showMedia(newIndex);
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addToAlbumBtn = document.getElementById('add-to-album-btn');
            const addToAlbumModal = document.getElementById('add-to-album-modal');
            const closeAlbumModal = document.getElementById('close-album-modal');
            const userAlbumsContainer = document.getElementById('user-albums-container');
            
            if (addToAlbumBtn) {
                addToAlbumBtn.addEventListener('click', function() {
                    addToAlbumModal.classList.remove('hidden');
                    
                    // Fetch user albums
                    fetch(`{{ route('users.albums', Auth::id()) }}?post_id={{ $post->id }}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            userAlbumsContainer.innerHTML = '';
                            
                            if (data.albums.length === 0) {
                                userAlbumsContainer.innerHTML = `
                                    <div class="text-center py-6">
                                        <p class="text-gray-500 mb-4">You don't have any albums yet.</p>
                                        <a href="{{ route('albums.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-full">
                                            Create an album
                                        </a>
                                    </div>
                                `;
                                return;
                            }
                            
                            const albumsList = document.createElement('div');
                            albumsList.className = 'space-y-2';
                            
                            data.albums.forEach(album => {
                                const albumElement = document.createElement('div');
                                albumElement.className = 'flex items-center justify-between p-3 border rounded-md hover:bg-gray-50';
                                
                                let coverHtml = `<div class="bg-gray-200 w-12 h-12 rounded-md flex items-center justify-center"><i class="fas fa-images text-gray-400"></i></div>`;
                                if (album.cover_image) {
                                    coverHtml = `<img src="/storage/${album.cover_image}" alt="${album.title}" class="w-12 h-12 object-cover rounded-md">`;
                                }
                                
                                let buttonHtml = `
                                    <button class="add-to-album-btn bg-red-600 hover:bg-red-700 text-white font-medium py-1 px-3 rounded-full text-sm" data-album-id="${album.id}">
                                        Add
                                    </button>
                                `;
                                
                                if (album.has_post) {
                                    buttonHtml = `
                                        <button class="bg-gray-300 text-gray-600 font-medium py-1 px-3 rounded-full text-sm" disabled>
                                            Added
                                        </button>
                                    `;
                                }
                                
                                albumElement.innerHTML = `
                                    <div class="flex items-center">
                                        ${coverHtml}
                                        <div class="ml-3">
                                            <h4 class="font-medium">${album.title}</h4>
                                            <p class="text-xs text-gray-500">${album.posts_count} posts</p>
                                        </div>
                                    </div>
                                    ${buttonHtml}
                                `;
                                
                                albumsList.appendChild(albumElement);
                            });
                            
                            userAlbumsContainer.appendChild(albumsList);
                            
                            // Add event listeners to the "Add" buttons
                            document.querySelectorAll('.add-to-album-btn').forEach(btn => {
                                btn.addEventListener('click', function() {
                                    const albumId = this.getAttribute('data-album-id');
                                    addPostToAlbum(albumId, this);
                                });
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching albums:', error);
                            userAlbumsContainer.innerHTML = `
                                <div class="text-center py-6">
                                    <p class="text-red-500">Error loading albums. Please try again.</p>
                                </div>
                            `;
                        });
                });
            }
            
            if (closeAlbumModal) {
                closeAlbumModal.addEventListener('click', function() {
                    addToAlbumModal.classList.add('hidden');
                });
            }
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === addToAlbumModal) {
                    addToAlbumModal.classList.add('hidden');
                }
            });
            
            function addPostToAlbum(albumId, buttonElement) {
                const originalText = buttonElement.innerHTML;
                buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                buttonElement.disabled = true;
                
                fetch(`/albums/${albumId}/posts`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ post_id: {{ $post->id }} })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        buttonElement.innerHTML = '<i class="fas fa-check"></i>';
                        buttonElement.classList.remove('bg-red-600', 'hover:bg-red-700');
                        buttonElement.classList.add('bg-green-500', 'hover:bg-green-600');
                        
                        // Disable the button after successful addition
                        buttonElement.disabled = true;
                    } else {
                        buttonElement.innerHTML = originalText;
                        buttonElement.disabled = false;
                        alert(data.message || 'Failed to add post to album');
                    }
                })
                .catch(error => {
                    console.error('Error adding post to album:', error);
                    buttonElement.innerHTML = originalText;
                    buttonElement.disabled = false;
                    alert('An error occurred. Please try again.');
                });
            }
        });
    </script>
@endsection
