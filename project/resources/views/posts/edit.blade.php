@extends('layouts.app')

@section('title', 'Edit Pin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-6">Edit Pin</h1>

                <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column - Media Upload -->
                        <div>
                            <div class="mb-4">
                                <label for="media" class="block text-gray-700 font-medium mb-2">Current Media</label>
                                
                                <!-- Current Media Preview -->
                                @if($post->mediaContent->isNotEmpty())
                                    <div class="mb-4">
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($post->mediaContent as $media)
                                                <div class="relative">
                                                    @if(Str::startsWith($media->type, 'image/'))
                                                        <img src="{{ asset('storage/' . $media->path) }}" 
                                                            alt="Current image" 
                                                            class="h-40 w-full object-cover rounded">
                                                    @elseif(Str::startsWith($media->type, 'video/'))
                                                        <div class="relative">
                                                            <video 
                                                                src="{{ asset('storage/' . $media->path) }}" 
                                                                class="h-40 w-full object-cover rounded"
                                                                muted>
                                                            </video>
                                                            <div class="absolute inset-0 flex items-center justify-center">
                                                                <i class="fas fa-play-circle text-white text-3xl opacity-80"></i>
                                                            </div>
                                                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 text-center">
                                                                Video
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="text-gray-600 text-sm mt-2">{{ $post->mediaContent->count() }} file(s) currently attached</p>
                                    </div>
                                @else
                                    <p class="text-gray-600 text-sm mb-4">No media files currently attached</p>
                                @endif
                                
                                <label for="media" class="block text-gray-700 font-medium mb-2">Upload New Media (Optional)</label>
                                <div class="border-2 border-gray-300 rounded-lg p-6 text-center"
                                    onclick="document.getElementById('media').click()">
                                    <div id="preview" class="hidden mb-4">
                                        <!-- Preview content will be inserted here -->
                                    </div>
                                    <div id="upload-prompt">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-2"></i>
                                        <p class="text-gray-500">Click to upload new media (optional)</p>
                                        <p class="text-gray-400 text-sm mt-1">Uploading new files will replace all current media</p>
                                        <p class="text-gray-400 text-sm mt-1">Recommendation: Use high-quality .jpg files or .mp4 videos less than 20MB</p>
                                    </div>
                                    <input type="file" id="media" name="media[]" class="hidden"
                                        accept="image/*,video/*" multiple>
                                </div>
                                @error('media')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column - Pin Details -->
                        <div>
                            <div class="mb-4">
                                <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('title') border-red-500 @enderror"
                                    required>
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('description') border-red-500 @enderror">{{ old('description', $post->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="tags" class="block text-gray-700 font-medium mb-2">Tags (space separated)</label>
                                <input type="text" id="tags" name="tags" 
                                    value="{{ old('tags', $post->tags->pluck('name')->implode(' ')) }}"
                                    placeholder="nature travel photography"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('tags') border-red-500 @enderror">
                                @error('tags')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <a href="{{ route('posts.show', $post->id) }}"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-full mr-2">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-full">
                                    Update Pin
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mediaInput = document.getElementById('media');
            const preview = document.getElementById('preview');
            const uploadPrompt = document.getElementById('upload-prompt');

            // Handle file selection
            mediaInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    // Clear previous previews
                    preview.innerHTML = '';
                    
                    // Create a container for previews
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'grid grid-cols-2 gap-2';
                    preview.appendChild(previewContainer);
                    
                    // Show preview area and hide upload prompt
                    preview.classList.remove('hidden');
                    uploadPrompt.classList.add('hidden');
                    
                    // Process each file
                    Array.from(this.files).forEach(file => {
                        const reader = new FileReader();
                        const previewItem = document.createElement('div');
                        previewItem.className = 'relative';
                        
                        // Check if the file is an image or video
                        if (file.type.startsWith('image/')) {
                            // Handle image file
                            reader.onload = function(e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.className = 'h-40 w-full object-cover rounded';
                                img.alt = 'Image Preview';
                                previewItem.appendChild(img);
                            };
                        } else if (file.type.startsWith('video/')) {
                            // Handle video file
                            reader.onload = function(e) {
                                const video = document.createElement('video');
                                video.src = e.target.result;
                                video.className = 'h-40 w-full object-cover rounded';
                                video.controls = false;
                                video.muted = true;
                                video.autoplay = false;
                                
                                // Add play icon overlay
                                const playIcon = document.createElement('div');
                                playIcon.className = 'absolute inset-0 flex items-center justify-center';
                                playIcon.innerHTML = '<i class="fas fa-play-circle text-white text-3xl opacity-80"></i>';
                                
                                previewItem.appendChild(video);
                                previewItem.appendChild(playIcon);
                                
                                // Add video label
                                const videoLabel = document.createElement('div');
                                videoLabel.className = 'absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 text-center';
                                videoLabel.textContent = 'Video';
                                previewItem.appendChild(videoLabel);
                            };
                        }
                        
                        reader.readAsDataURL(file);
                        previewContainer.appendChild(previewItem);
                    });
                    
                    // Add file count indicator
                    const fileCount = document.createElement('p');
                    fileCount.className = 'text-gray-600 text-sm mt-2';
                    fileCount.textContent = `${this.files.length} new file(s) selected`;
                    preview.appendChild(fileCount);
                    
                    // Add note about replacing existing media
                    const replaceNote = document.createElement('p');
                    replaceNote.className = 'text-red-500 text-sm mt-1';
                    replaceNote.textContent = 'Note: Uploading new files will replace all current media';
                    preview.appendChild(replaceNote);
                }
            });
            
            // Initialize video preview functionality for existing videos
            const existingVideos = document.querySelectorAll('video');
            existingVideos.forEach(video => {
                const container = video.closest('.relative');
                if (container) {
                    container.addEventListener('mouseenter', () => {
                        video.play().catch(e => console.log("Play on hover failed:", e));
                    });
                    
                    container.addEventListener('mouseleave', () => {
                        video.pause();
                        video.currentTime = 0;
                    });
                }
            });
        });
    </script>
@endsection
