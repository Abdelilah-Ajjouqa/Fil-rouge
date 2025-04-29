@props(['post'])

<div>
    <h3 class="font-semibold mb-4">Comments</h3>

    @auth
        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-6">
            @csrf
            <div class="flex items-center">
                <img src="{{ Auth::user()->avatar ?? 'https://placehold.co/40' }}" alt="User"
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

    <div class="space-y-2 max-h-64">
        @forelse($post->comments ?? [] as $comment)
            <div class="flex items-center">
                <a href="{{ route('users.show', $comment->user_id) }}">
                    <img src="{{ $comment->user->avatar ?? 'https://placehold.co/40' }}" alt="User"
                        class="w-8 h-8 rounded-full mr-2">
                </a>
                <div class="bg-gray-100 rounded-2xl px-4 py-[5px] flex-grow flex justify-between">
                    <div>
                        <div class="flex justify-between items-start">
                            <a href="{{ route('users.show', $comment->user_id) }}"
                                class="font-semibold text-sm">{{ $comment->user->username }}</a>
                            {{-- <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span> --}}
                        </div>
                        <p class="text-gray-700">{{ $comment->content }}</p>
                    </div>
                    <span class="flex items-center gap-2 h-full">
                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>

                        @if (Auth::check() && (Auth::id() == $comment->user_id || Auth::user()->role == 'admin'))
                            <div class="relative " x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                    class="absolute right-0 bottom-5 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                                    <form action="{{ route('comments.destroy', [$post->id, $comment->id]) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
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
                        
                    </sp>
                </div>

                {{-- @if (Auth::check() && (Auth::id() == $comment->user_id || Auth::user()->role == 'admin'))
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
                @endif --}}
            </div>
        @empty
            <p class="text-center text-gray-500">No comments yet</p>
        @endforelse
    </div>
</div>
