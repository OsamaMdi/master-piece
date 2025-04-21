<!DOCTYPE html>
<html lang="en">
<head>



    <meta charset="UTF-8">
    <title>Merchant Dashboard</title>
    <!-- Font Awesome 6 Free CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="upload-id-route" content="{{ route('admin.users.upload.identity') }}">
    <meta name="show-upload-modal" content="{{ session('showUploadModal') ? 'true' : 'false' }}">
    <meta name="new-product-id" content="{{ session('newProductId') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
    <!-- CSS -->


<!-- Favicon -->
<link rel="icon" href="{{ asset('img/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/merchant-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('admin.partials.sidebar')
    @include('admin.partials.navbar')


    <main id="main-content" class="main-content transition-all duration-300">
        @yield('content')
    </main>

    <!-- JS -->
    <script src="{{ asset('js/merchant-script.js') }}"></script>
    <script src="{{ asset('js/user.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
