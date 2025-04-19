@include('layouts.header')

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white px-8 py-4 rounded-2xl shadow-lg w-full max-w-md space-y-6">

        <!-- Logo -->
        <div class="text-center">
            <img src="{{ asset('logo pinterest - noir rond.jpeg') }}" alt="Logo" class="mx-auto w-16 h-16 ">
            <h2 class="text-2xl font-semibold text-gray-800">Create your account</h2>
            <p class="text-sm text-gray-500">Join our creative world</p>
        </div>

        <!-- Register Form -->
        <form action="{{ route('auth.register') }}" method="post" class="space-y-4">
            @csrf

            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First name</label>
                <input type="text" name="first_name" id="first_name"
                    class="py-1.5 px-2 mt-1 w-full rounded-sm shadow-sm focus:ring-black focus:ring-1">
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last name</label>
                <input type="text" name="last_name" id="last_name"
                    class="py-1.5 px-2 mt-1 w-full rounded-sm shadow-sm focus:ring-black focus:ring-1">
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username"
                    class="py-1.5 px-2 mt-1 w-full rounded-sm shadow-sm focus:ring-black focus:ring-1">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                    class="py-1.5 px-2 mt-1 w-full rounded-sm shadow-sm focus:ring-black focus:ring-1">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                    class="py-1.5 px-2 mt-1 w-full rounded-sm shadow-sm focus:ring-black focus:ring-1">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Password
                    confirmation</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="py-1.5 px-2 mt-1 w-full rounded-sm shadow-sm focus:ring-black focus:ring-1">
            </div>

            <button type="submit"
                class="w-full bg-black text-white py-2 px-4 rounded-xl hover:bg-pink-600 transition-colors">
                Create Account
            </button>
        </form>

        <!-- Already have account -->
        <p class="text-sm text-center text-gray-600">
            Already have an account?
            <a href="{{ route('auth.login.form') }}" class="text-pink-500 hover:underline hover:text-black">Log in</a>
        </p>
    </div>
</div>
