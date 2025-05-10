<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="upload-id-route" content="{{ route('upload.id.ajax') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Meta for Upload Script -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="upload-id-route" content="{{ route('upload.id.ajax') }}">
<!-- Favicon -->
<link rel="icon" href="{{ asset('img/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Tailwind & Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/auth.js'])  {{-- npm run dev --}}


    <!-- Custom Styles -->
    <link rel="stylesheet" href="/css/auth.css">
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100">

    <!-- Background Decoration -->
    <div class="background-decor"></div>

    <!-- Page Content Centered -->
    <div class="flex items-center justify-center min-h-screen relative z-10">

        <div class="auth-card p-8 bg-white rounded-lg shadow-lg flex flex-col items-center">

            <!-- Logo -->
            <div class="text-3xl font-bold mb-8 text-gray-800">
                <span class="text-indigo-600">R</span>entify
            </div>

            <!-- Dynamic Page Content (Form Content) -->
            <div class="w-full">
                {{ $slot }}
            </div>

        </div>

    </div>

</body>
</html>
