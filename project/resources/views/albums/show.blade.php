@extends('layouts.app')

@section('title', $album->title)

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('albums.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold">{{ $album->title }}</h1>
                    @if($album->is_private)
                        <span class="ml-2 bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full flex items-center">
                            <i class="fas fa-lock text-xs mr-1"></i> Private
                        </span>
                    @endif
                </div>
                
                @if(Auth::id() === $album->user_id)
                    <div class="flex space-x-2">
                        <a href="{{ route('albums.edit', $album->id) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-full">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('albums.destroy', $album->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this album?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-full">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            
            @if($album->description)
                <p class="text-gray-600 mt-2">{{ $album->description }}</p>
            @endif
            
            <div class="flex items-center mt-4 text-sm text-gray-600">
                <span>Created by {{ $album->user->username }}</span>
                <span class="mx-2">•</span>
                <span>{{ $album->posts->count() }} {{ Str::plural('post', $album->posts->count()) }}</span>
                <span class="mx-2">•</span>
                <span>{{ $album->created_at->diffForHumans() }}</span>
            </div>
        </div>
        
        @if(Auth::id() === $album->user_id)
            <div class="mb-6">
                <button id="add-posts-btn" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-full">
                    <i class="fas fa-plus mr-1"></i> Add Posts
                </button>
            </div>
            
            <!-- Add Posts Modal (hidden by default) -->
            <div id="add-posts-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[80vh] overflow-hidden">
                    <div class="flex justify-between items-center border-b p-4">
                        <h3 class="text-lg font-semibold">Add Posts to Album</h3>
                        <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="p-4 overflow-y-auto max-h-[60vh]" id="user-posts-container">
                        <div class="flex justify-center items-center h-32">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Posts Grid -->
        <div class="masonry-grid">
            @forelse($album->posts as $post)
                <div class="masonry-item">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <a href="{{ route('posts.show', $post->id) }}" class="block">
                            @if($post->mediaContent->isNotEmpty())
                                <img src="{{ asset('storage/' . $post->mediaContent->first()->path) }}" alt="{{ $post->title }}" class="w-full object-cover">
                            @else
                                <div class="bg-gray-200 w-full h-48"></div>
                            @endif
                        </a>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg truncate">{{ $post->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 mt-1">{{ $post->description }}</p>
                            
                            <div class="flex items-center justify-between mt-3">
                                <div class="flex items-center">
                                    <img src="{{ $post->user->avatar ?? 'https://via.placeholder.com/40' }}" alt="User" class="w-8 h-8 rounded-full mr-2">
                                    <span class="text-sm font-medium">{{ $post->user->username }}</span>
                                </div>
                                
                                @if(Auth::id() === $album->user_id)
                                    <form action="{{ route('albums.posts.remove', ['id' => $album->id, 'post_id' => $post->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-600 hover:text-red-600 p-1 rounded-full hover:bg-gray-100">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <div class="text-gray-500 mb-4">
                        <i class="fas fa-images text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">No posts in this album yet</h3>
                    <p class="text-gray-600 mb-4">Add posts to this album to see them here</p>
                    <button id="add-posts-btn" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-full">
                        Add posts
                    </button>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addPostsBtn = document.getElementById('add-posts-btn');
            const addPostsModal = document.getElementById('add-posts-modal');
            const closeModal = document.getElementById('close-modal');
            const userPostsContainer = document.getElementById('user-posts-container');
            
            if (addPostsBtn) {
                addPostsBtn.addEventListener('click', function() {
                    addPostsModal.classList.remove('hidden');
                    
                    // Fetch user posts that are not in this album
                    fetch(`{{ route('users.posts', Auth::id()) }}?album_id={{ $album->id }}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            userPostsContainer.innerHTML = '';
                            
                            if (data.posts.length === 0) {
                                userPostsContainer.innerHTML = `
                                    <div class="text-center py-6">
                                        <p class="text-gray-500">You don't have any posts to add to this album.</p>
                                    </div>
                                `;
                                return;
                            }
                            
                            const grid = document.createElement('div');
                            grid.className = 'grid grid-cols-2 sm:grid-cols-3 gap-4';
                            
                            data.posts.forEach(post => {
                                const postElement = document.createElement('div');
                                postElement.className = 'bg-white border rounded-lg overflow-hidden';
                                
                                let mediaHtml = `<div class="bg-gray-200 h-32"></div>`;
                                if (post.media_content && post.media_content.length > 0) {
                                    mediaHtml = `<img src="/storage/${post.media_content[0].path}" alt="${post.title}" class="w-full h-32 object-cover">`;
                                }
                                
                                postElement.innerHTML = `
                                    <div class="relative">
                                        ${mediaHtml}
                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 hover:bg-opacity-30 transition-opacity">
                                            <button class="add-to-album-btn opacity-0 hover:opacity-100 bg-white text-gray-800 font-medium py-1 px-3 rounded-full text-sm" data-post-id="${post.id}">
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <h4 class="font-medium text-sm truncate">${post.title}</h4>
                                    </div>
                                `;
                                
                                grid.appendChild(postElement);
                            });
                            
                            userPostsContainer.appendChild(grid);
                            
                            // Add event listeners to the "Add" buttons
                            document.querySelectorAll('.add-to-album-btn').forEach(btn => {
                                btn.addEventListener('click', function() {
                                    const postId = this.getAttribute('data-post-id');
                                    addPostToAlbum(postId, this);
                                });
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching posts:', error);
                            userPostsContainer.innerHTML = `
                                <div class="text-center py-6">
                                    <p class="text-red-500">Error loading posts. Please try again.</p>
                                </div>
                            `;
                        });
                });
            }
            
            if (closeModal) {
                closeModal.addEventListener('click', function() {
                    addPostsModal.classList.add('hidden');
                });
            }
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === addPostsModal) {
                    addPostsModal.classList.add('hidden');
                }
            });
            
            function addPostToAlbum(postId, buttonElement) {
                const originalText = buttonElement.innerHTML;
                buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                buttonElement.disabled = true;
                
                fetch(`{{ route('albums.posts.add', $album->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ post_id: postId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        buttonElement.innerHTML = '<i class="fas fa-check"></i>';
                        buttonElement.classList.remove('bg-white', 'text-gray-800');
                        buttonElement.classList.add('bg-green-500', 'text-white');
                        
                        // Reload the page after a short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
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