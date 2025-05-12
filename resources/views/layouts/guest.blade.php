<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="upload-id-route" content="{{ route('upload.id.ajax') }}">

    <!-- Title -->
    <title>Rentify - Secure Login & Registration</title>

    <!-- Description -->
    <meta name="description"
        content="Login to your Rentify account or register to start managing your tool rentals with ease.">

    <!-- Open Graph -->
    <meta property="og:title" content="Rentify - Secure Login & Registration" />
    <meta property="og:description"
        content="Login to your Rentify account or register to start managing your tool rentals with ease." />
    <meta property="og:image" content="{{ asset('img/logo.png') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">


@vite([
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/js/auth.js'
])
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            <div class="text-center mb-4">
                <a class="nav-brand" href="{{ route('home') }}">
                    <img src="{{ asset('img/logof.png') }}" alt="Rentify Logo" class="logo-img"
                        style="max-width: 150px; height: auto;">
                </a>
            </div>


            <!-- Dynamic Page Content (Form Content) -->
            <div class="w-full">
                {{ $slot }}
            </div>

        </div>

    </div>
  <!-- ✅ SweetAlert Messages -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    @if($errors->has('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ $errors->first('error') }}",
            });

        </script>
    @endif
</body>

</html>
