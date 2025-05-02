<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservation Notice</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
    Swal.fire({
        icon: "{{ $type }}",  // success, error, warning
        title: "{{ $title }}",
        text: "{{ $message }}",
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = "{{ route('home') }}"; // هنا التحويل إلى الهوم
    });
</script>
</body>
</html>
