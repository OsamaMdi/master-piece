<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">

    <meta charset="UTF-8">
    <title>Merchant Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="show-upload-modal" content="{{ session('showUploadModal') ? 'true' : 'false' }}">
    <meta name="new-product-id" content="{{ session('newProductId') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
    <!-- CSS -->

<!-- Favicon -->
<link rel="icon" href="{{ asset('img/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/merchant-style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('merchants.partials.sidebar')
    @include('merchants.partials.navbar')


    <main id="main-content" class="main-content transition-all duration-300">
        @yield('content')
    </main>

    <script src="{{ asset('js/merchantSearch.js') }}"></script>
    <!-- JS -->
    <script src="{{ asset('js/merchant-script.js') }}" defer></script>

    <script src="{{ asset('js/poppProductM.js') }}" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: @json(session('success')),
                    timer: 2500,
                    showConfirmButton: false
                });
            @elseif(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: @json(session('error')),
                    timer: 2500,
                    showConfirmButton: false
                });
            @elseif(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: @json(session('warning')),
                    timer: 2500,
                    showConfirmButton: false
                });
            @elseif(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Info',
                    text: @json(session('info')),
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
</body>
</html>
