<!DOCTYPE html>
<html lang="en">
<head>


    <!-- Favicon -->
<link rel="icon" href="{{ asset('img/logo.png') }}">

    ...
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">



    <meta charset="UTF-8">
    <title>Merchant Dashboard</title>
    <!-- Font Awesome 6 Free CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="upload-id-route" content="{{ route('admin.users.upload.identity') }}">
    <meta name="show-upload-modal" content="{{ session('showUploadModal') ? 'true' : 'false' }}">
    <meta name="new-product-id" content="{{ session('newProductId') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
    <!-- CSS -->


    @vite('resources/js/app.js')
   {{--  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"> --}}
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
    @stack('scripts')
    <!-- JS -->
    <script src="{{ asset('js/merchant-script.js') }}"></script>
    <script src="{{ asset('js/user.js') }}"></script>
    <script src="{{ asset('js/adminSearch.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        window.userId = {{ auth()->id() }};
    </script>

@if(session('success') || session('error') || session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // استخدم نوع التنبيه بناءً على نوع الرسالة
        let alertType = '';
        let alertTitle = '';
        let alertMessage = '';

        @if(session('success'))
            alertType = 'success';
            alertTitle = 'Success';
            alertMessage = "{{ session('success') }}";
        @elseif(session('error'))
            alertType = 'error';
            alertTitle = 'Error';
            alertMessage = "{{ session('error') }}";
        @elseif(session('warning'))
            alertType = 'warning';
            alertTitle = 'Warning';
            alertMessage = "{{ session('warning') }}";
        @endif

        if (!sessionStorage.getItem('alertShown')) {
            Swal.fire({
                icon: alertType,
                title: alertTitle,
                text: alertMessage,
                timer: alertType === 'success' ? 2000 : undefined,
                showConfirmButton: alertType !== 'success'
            });

            // منع التكرار عند الرجوع للصفحة
            sessionStorage.setItem('alertShown', '1');

            // مسح الفلاق بعد ثواني (اختياري)
            setTimeout(() => {
                sessionStorage.removeItem('alertShown');
            }, 5000);
        }

        // أيضًا نحذف بيانات الجلسة من الـ URL للتأكد
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });
</script>
@endif

</body>
</html>
