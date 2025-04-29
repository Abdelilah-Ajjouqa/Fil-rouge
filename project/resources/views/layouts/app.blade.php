<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Pinterest Clone') }} - @yield('title', 'Home')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('styles')
</head>

<body class="layout-container">
    @include('layouts.header')

    <!-- Main Content -->
    <main class="container mx-auto px-4 pt-20 pb-10">
        @if (session('success'))
            <div class="alert-success border rounded mb-4 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert-error border rounded mb-4 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @include('layouts.footer')

    @yield('scripts')
</body>

</html>
