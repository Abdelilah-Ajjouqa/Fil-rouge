<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="bg-white">
    <nav class="sticky top-0 z-50 bg-white shadow-sm mx-auto py-3 flex items-center justify-evenly">
        <!-- Logo Section -->
        <div class="flex items-center space-x-8">
            <!-- Logo -->
            <div class="logo w-10 h-10 rounded-full shadow-lg flex justify-center items-center">
                <img src="{{ asset('logo pinterest - noir rond.jpeg') }}" alt="Logo"
                    class="w-full h-full object-cover rounded-full">
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="#"
                    class="nav-link-active px-5 py-2 text-white flex justify-center items-center bg-neutral-900 rounded-3xl duration-300 hover:drop-shadow-md hover:scale-105 hover:bg-white hover:rounded-3xl hover:text-black">
                    <span class="text-center font-semibold font-['Roboto']">Explore</span>
                </a>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="flex-1 max-w-2xl mx-4 hidden lg:block">
            <div class="relative flex items-center">
                <i class="ri-search-line absolute left-4 text-gray-500"></i>
                <input type="text" placeholder="Search"
                    class="w-full h-12 bg-gray-200 rounded-3xl pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-black focus:bg-white" />
            </div>
        </div>

        <!-- Login/Register Buttons -->
        <div class="flex items-center space-x-4">
            <a href="/login"
                class="px-4 py-2 text-black font-semibold rounded-3xl duration-300 hover:bg-gray-100">
                Log in
            </a>
            <a href="/register"
                class="px-4 py-2 text-white font-semibold bg-neutral-900 rounded-3xl duration-300 hover:bg-neutral-800">
                Sign up
            </a>
        </div>

        <!-- Mobile Menu Button (hidden on larger screens) -->
        <button class="md:hidden text-gray-600">
            <i class="ri-menu-line text-2xl"></i>
        </button>
    </nav>
</body>

</html>
