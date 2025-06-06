<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>Rentify - Merchant Dashboard</title>

    <!-- Description -->
    <meta name="description" content="Easily manage your products, reservations, and clients with Rentify's smart merchant dashboard.">

    <!-- Open Graph Meta (for Facebook/WhatsApp preview) -->
    <meta property="og:title" content="Rentify - Merchant Dashboard" />
    <meta property="og:description" content="Easily manage your products, reservations, and clients with Rentify." />
    <meta property="og:image" content="{{ asset('img/logo.png') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />

    <!-- CSRF & Session Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="show-upload-modal" content="{{ session('showUploadModal') ? 'true' : 'false' }}">
    <meta name="new-product-id" content="{{ session('newProductId') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/merchant-style.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
 
    @vite('resources/js/app.js')

    <!-- Scripts -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>
</head>

<body>
    @include('merchants.partials.sidebar')
    @include('merchants.partials.navbar')


    <main id="main-content" class="main-content transition-all duration-300">
        @yield('content')
    </main>

    @stack('scripts')

    <script src="{{ asset('js/merchantSearch.js') }}"></script>
    <!-- JS -->
    <script src="{{ asset('js/merchant-script.js') }}" defer></script>

    <script src="{{ asset('js/poppProductM.js') }}" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.userId = {{ auth()->id() }};
    </script>
       <script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            sessionStorage.removeItem('success');
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: @json(session('success')),
                timer: 2500,
                showConfirmButton: false
            });
        @elseif(session('error'))
            sessionStorage.removeItem('error');
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: @json(session('error')),
                timer: 2500,
                showConfirmButton: false
            });
        @elseif(session('warning'))
            sessionStorage.removeItem('warning');
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: @json(session('warning')),
                timer: 2500,
                showConfirmButton: false
            });
        @elseif(session('info'))
            sessionStorage.removeItem('info');
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

@auth
<script>
    window.userId = {{ auth()->id() }};
    window.csrfToken = '{{ csrf_token() }}';

    function updateChatUnreadCount() {
        fetch('{{ route('chat.unreadCount') }}')
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('chat-unread-count');
                if (!badge) return;
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }
                console.log('🔄 Updated unread count:', data.count);
            }).catch(err => {
                console.warn('❌ Failed to fetch unread count:', err);
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateChatUnreadCount();
        setInterval(updateChatUnreadCount, 10000); // كل 10 ثواني

        // ✅ تحديث حالة التوصيل كل 30 ثانية
        setInterval(() => {
            console.log('📤 Sending mark-delivered request...');
            fetch('/chat/mark-delivered', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({ auto: true })
            })
            .then(res => res.json())
            .then(data => {
                console.log('✅ Delivered updated:', data.updated_ids);
            })
            .catch(err => {
                console.error('❌ Failed to mark as delivered:', err);
            });
        }, 30000);

        if (typeof Echo !== 'undefined' && typeof window.userId !== 'undefined') {
            Echo.private('user.' + window.userId)
                .listen('.MessageSent', () => {
                    console.log('📩 New message received - updating count');
                    updateChatUnreadCount();
                });

            @foreach(auth()->user()->all_chats ?? [] as $chat)
                Echo.private('chat.{{ $chat->id }}')
                    .listen('.MessageDeliveredStatusUpdated', (e) => {
                        console.log('📬 Received delivery status update:', e);

                        if (parseInt(window.userId) !== parseInt(e.sender_id)) return;

                        const msgEl = document.querySelector(`.message-sent[data-id="${e.message_id}"]`);
                        if (msgEl) {
                            const readStatus = msgEl.querySelector('.seen-status');
                            if (readStatus && !msgEl.classList.contains('delivered')) {
                                readStatus.textContent = '✔️';
                                readStatus.style.color = '#999';
                                msgEl.classList.add('delivered');
                                console.log('✅ Message marked as delivered visually:', e.message_id);
                            }
                        } else {
                            console.warn('⚠️ Message element not found in DOM for ID:', e.message_id);
                        }
                    });
            @endforeach
        } else {
            console.warn('⚠️ Echo or userId not defined');
        }
    });
</script>

<script>
    document.addEventListener('click', () => {
        fetch('/chat/mark-delivered', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({ trigger: 'click' })
        })
        .then(res => res.json())
        .then(data => {
            console.log('🟢 Delivered update sent on click:', data);
        })
        .catch(err => {
            console.error('❌ Failed to send delivered update on click:', err);
        });
    });


    </script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        ['success', 'error', 'warning', 'info'].forEach(type => {
            const message = sessionStorage.getItem(type);
            if (message) {
                Swal.fire({
                    icon: type,
                    title: type.charAt(0).toUpperCase() + type.slice(1),
                    text: message,
                    timer: 2500,
                    showConfirmButton: false
                });
                sessionStorage.removeItem(type);
            }
        });
    });
    </script>
@endauth


</body>
</html>
