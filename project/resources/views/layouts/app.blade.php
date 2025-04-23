<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Pinterest Clone') }} - @yield('title', 'Home')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .masonry-grid {
            column-count: 1;
            column-gap: 16px;
        }

        @media (min-width: 640px) {
            .masonry-grid {
                column-count: 2;
            }
        }

        @media (min-width: 768px) {
            .masonry-grid {
                column-count: 3;
            }
        }

        @media (min-width: 1024px) {
            .masonry-grid {
                column-count: 4;
            }
        }

        @media (min-width: 1280px) {
            .masonry-grid {
                column-count: 5;
            }
        }

        .masonry-item {
            display: inline-block;
            width: 100%;
            /* break-inside: avoid; */
            margin-bottom: 16px;
        }
    </style>
    @yield('styles')
</head>

<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-md py-4 fixed w-full z-10">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="{{ route('posts.index') }}" class="text-red-600 font-bold text-2xl">
                <i class="fab fa-pinterest text-red-600 mr-2"></i>
                PinClone
            </a>

            <div class="hidden md:block flex-grow mx-4">
                <div class="relative">
                    <input type="text" placeholder="Search"
                        class="w-full bg-gray-100 rounded-full py-2 px-4 pl-10 focus:outline-none focus:ring-2 focus:ring-red-500">
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
                            <img src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/40' }}" alt="Profile"
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
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-full">Sign up</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 pt-20 pb-10">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p class="text-gray-600">&copy; {{ date('Y') }} PinClone. All rights reserved.</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-600 hover:text-red-600">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-red-600">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-red-600">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>

</html>
