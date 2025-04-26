
@include('users.navbar')

<div class="blocked-background">
    @yield('content')
</div>


@if (session('blocked_message'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Blocked',
                text: '{{ session('blocked_message') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        });
    </script>
@endif


</body>
</html>
