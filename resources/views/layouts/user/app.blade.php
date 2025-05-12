

 @include('users.navbar')

  @yield('content')


  @vite('resources/js/app.js')
  @stack('scripts')



  @auth
<script>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        ❌ {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        ⚠️ {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


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

@endauth

 @if (!Request::is('chat') && !Request::is('chat/*'))
    @include('users.footer')
@endif




  @include('components.website-feedback-modal')
