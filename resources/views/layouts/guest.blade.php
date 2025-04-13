<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts (Tailwind, App) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Auth Style -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="{{ asset('js/auth.js') }}" defer></script>

</head>
<body class="font-sans text-gray-900 antialiased">

    <div class="flex h-screen">
        <!-- Left Section (optional, you can delete it if not needed) -->
        <div class="flex-1 hidden lg:flex items-center justify-center bg-gray-100">
            <!-- You can put an image or text here -->
            <!-- <img src="{{ asset('images/login-image.png') }}" alt="Welcome" class="h-2/3"> -->
        </div>

        <!-- Right Section (Login/Register Form) -->
        <div class="w-full lg:w-1/3 flex flex-col justify-center items-center bg-gray-900 p-10">
            <!-- Logo -->
            <div class="text-3xl font-bold mb-10 text-white">
                <span class="text-indigo-600">R</span>entify
            </div>

            <!-- Page Content -->
            <div class="w-full">
                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
