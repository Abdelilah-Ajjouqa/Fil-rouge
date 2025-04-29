<!-- Navigation -->
<nav class="bg-white shadow-md py-4 fixed w-full z-10">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="{{ route('posts.index') }}" class="text-black font-bold text-2xl">
            <i class="fab fa-pinterest text-black mr-2"></i>
            PinClone
        </a>

        <div class="hidden md:block flex-grow mx-4">
            <div class="relative">
                <input type="text" placeholder="Search"
                    class="w-full bg-gray-100 rounded-full py-2 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-700">
                <i class="fas fa-search absolute left-3 top-3 text-gray-500"></i>
            </div>
        </div>

        <div class="flex items-center space-x-4">
            <a href="{{ route('posts.index') }}" class="text-gray-700 hover:bg-gray-100 p-2 rounded-full">
                <i class="fas fa-home"></i>
            </a>

            @auth
                <a href="#" class="text-gray-700 hover:bg-gray-100 p-2 rounded-full">
                    <i class="fas fa-bell"></i>
                </a>
                <a href="#" class="text-gray-700 hover:bg-gray-100 p-2 rounded-full">
                    <i class="fas fa-comment-dots"></i>
                </a>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <img src="{{ Auth::user()->avatar ?? 'https://placehold.co/40' }}" alt="Profile"
                            class="w-8 h-8 rounded-full">
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                        <a href="{{ route('users.show', Auth::id()) }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('auth.login.form') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-full">Log in</a>
                <a href="{{ route('auth.register.form') }}"
                    class="bg-black hover:bg-blue-500 text-white font-semibold py-2 px-4 rounded-full">Sign up</a>
            @endauth
        </div>
    </div>
</nav> 