<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/merchant-style.css') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
     <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles and Scripts -->
    @vite([
        'resources/css/app.css',
        'resources/css/profile.css',
        'resources/js/app.js'
    ])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

<div class="min-h-screen flex flex-col">

    <!-- Navbar and Sidebar based on User Type -->
    @if (Auth::check())
        @php
            $user = Auth::user();
        @endphp

        @if ($user->user_type === 'admin')
            @include('admin.partials.navbar')
            @include('admin.partials.sidebar')

        @elseif ($user->user_type === 'merchant')
            @if (Auth::user()->status === 'active')
                @include('merchants.partials.navbar')
                @include('merchants.partials.sidebar')
            @else
                @include('users.navbar')
            @endif

        @elseif ($user->user_type === 'user')
            @include('users.navbar')
        @endif
    @endif

    <!-- Page Header (if exists) -->
    @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

</div>

<script src="{{ asset('js/merchant-script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/userSearch.js') }}"></script>

<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Are you sure?',
            text: "Once your account is deleted, all of its resources and data will be permanently removed. Please confirm your password to proceed.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-account-form').submit();
            }
        });
    }

    @if (session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('status') }}",
            timer: 2500,
            showConfirmButton: false
        });
    @endif
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const profileBtn = document.getElementById("profileButton");
        const profileMenu = document.getElementById("profileMenu");

        if (!profileBtn || !profileMenu) return;

        // عند الضغط على الصورة
        profileBtn.addEventListener("click", function (e) {
            e.stopPropagation(); // يمنع الإغلاق التلقائي
            profileMenu.classList.toggle("hidden");
            profileMenu.classList.toggle("block");
        });

        // عند الضغط خارج القائمة
        document.addEventListener("click", function (e) {
            if (!profileMenu.contains(e.target) && e.target !== profileBtn) {
                profileMenu.classList.remove("block");
                profileMenu.classList.add("hidden");
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchBtn = document.querySelector(".search-btn");
        const searchForm = document.querySelector(".search-form");

        if (searchBtn && searchForm) {
            searchBtn.addEventListener("click", function (e) {
                e.stopPropagation();
                searchForm.classList.toggle("search-form-active");
            });

            // إغلاق عند الضغط خارج الفورم
            document.addEventListener("click", function (e) {
                if (!searchForm.contains(e.target) && !searchBtn.contains(e.target)) {
                    searchForm.classList.remove("search-form-active");
                }
            });
        }
    });
</script>


</body>
</html>
