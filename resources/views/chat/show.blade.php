@extends(Auth::user()->user_type === 'merchant' ? 'layouts.merchants.app' : 'layouts.user.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">

@php
    $me = Auth::user();
    $otherUser = $chat->sender_id === $me->id && $chat->sender_type === get_class($me)
        ? $chat->receiver
        : $chat->sender;

    $otherImage = $otherUser->profile_picture
        ? asset('storage/' . $otherUser->profile_picture)
        : asset('img/default-user.png');

    $otherName = $otherUser->name ?? 'Unknown';
@endphp

<div class="chat-container">
    <!-- Chat Header -->
    <div class="chat-header">
        <img src="{{ $otherImage }}" class="chat-header-avatar" alt="{{ $otherName }}">
        <span class="chat-header-name">{{ $otherName }}</span>
    </div>

    <!-- Chat Messages -->
    <div id="chatBox" class="chat-messages">
        @foreach ($chat->messages as $message)
            @php
                $isMe = $message->sender_id === $me->id && $message->sender_type === get_class($me);
                $sender = $message->sender;
                $senderImage = $sender->profile_picture
                    ? asset('storage/' . $sender->profile_picture)
                    : asset('img/default-user.png');
            @endphp

            <div class="message {{ $isMe ? 'message-sent' : 'message-received' }} {{ $message->read ? 'read' : '' }}"
                 data-id="{{ (int) $message->id }}"
                 data-sender-id="{{ $message->sender_id }}"
                 data-sender-type="{{ $message->sender_type }}"
                 data-read="{{ $message->read ? '1' : '0' }}">

                @if(!$isMe)
                    <img src="{{ $senderImage }}" class="message-avatar" alt="Sender">
                @endif

                <div class="message-bubble {{ $isMe ? 'bubble-sent' : 'bubble-received' }}">
                    @if ($message->image_url)
                        <img src="{{ asset('storage/' . $message->image_url) }}" class="message-image" />
                    @endif

                    @if ($message->message)
                        <div class="message-text">{{ $message->message }}</div>
                    @endif

                    <div class="message-time">
                        {{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}

                        @if($isMe)
                        <span class="read-status seen-status text-success" >
                            @if($message->read)
                            ✔✔
                            @elseif($message->delivered)
                            ✔
                            @else
                                🕓
                            @endif
                        </span>
                    @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Message Input -->
    <div class="message-input">
        <form id="chatForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="chat_id" value="{{ $chat->id }}">

            <div class="input-group">
                <label class="file-upload-wrapper">
                    <input type="file" name="image" id="imageInput" accept="image/*">
                    <span class="upload-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M14,13V17H10V13H7L12,8L17,13H14M22,16A2,2 0 0,1 20,18H4A2,2 0 0,1 2,16V4C2,2.89 2.9,2 4,2H20A2,2 0 0,1 22,4V16M20,4H4V16H20V4Z"/>
                        </svg>
                    </span>
                </label>

                <input type="text" name="message" id="chatMessageInputField" class="text-input" placeholder="Type your message...">
                <button type="submit" class="send-button">
                    <svg viewBox="0 0 24 24">
                        <path d="M2,21L23,12L2,3V10L17,12L2,14V21Z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script Configuration -->
<script>
    window.userId = {{ Auth::id() }};
    window.chatId = {{ $chat->id }};
    window.chatChannel = 'chat.{{ $chat->id }}';
    window.csrfToken = '{{ csrf_token() }}';
    window.chatSendUrl = '{{ route('chat.send') }}';
</script>
<script src="{{ asset('js/chat.js') }}"></script>
@endsection

 {{-- ✅ سكربت محدث لتعامل مع read و delivered وتحديث الأيقونات عند الطرفين --}}
 {{-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatBox = document.getElementById('chatBox');
        if (!chatBox) return;

        let isScrolling = false;

        function updateReadStatus() {
            fetch('/chat/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({ chat_id: window.chatId })
            })
            .then(res => res.json())
            .then(data => {
                if (Array.isArray(data.updated_ids)) {
                    data.updated_ids.forEach(id => {
                        const msg = document.querySelector(`.message[data-id="${id}"]`);
                        if (msg) {
                            msg.classList.add('read');
                            msg.dataset.read = '1';
                            const readStatus = msg.querySelector('.read-status');
                            if (readStatus) {
                                readStatus.textContent = '✔️✔️';
                                readStatus.style.color = 'green';
                            }
                        }
                    });
                }
            });
        }

        function updateDeliveredStatus() {
            const unreadMessages = Array.from(document.querySelectorAll('.message-received:not(.read)'))
                .filter(msg => msg.dataset.read === '0');

            const messageIds = unreadMessages.map(msg => parseInt(msg.dataset.id));
            if (messageIds.length === 0) return;

            messageIds.forEach(id => {
                fetch('/chat/mark-delivered', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ message_id: id })
                });
            });
        }

        updateReadStatus();
        updateDeliveredStatus();

        chatBox.addEventListener('scroll', () => {
            isScrolling = true;
            updateReadStatus();
            updateDeliveredStatus();
            setTimeout(() => isScrolling = false, 100);
        });

        setInterval(() => {
            if (!isScrolling) {
                updateReadStatus();
                updateDeliveredStatus();
            }
        }, 2000);
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof Echo === 'undefined') return;

        Echo.private(`chat.${window.chatId}`)
            .listen('.MessageSent', function (e) {
                if (e.receiver_id !== window.userId) return;

                fetch('/chat/mark-delivered', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ message_id: e.message.id })
                })
                .then(() => setTimeout(updateReadStatus, 500));
            })
            .listen('.MessageDeliveredStatusUpdated', function (e) {
                if (window.userId !== e.sender_id) return;
                const msgEl = document.querySelector(`.message-sent[data-id="${e.message_id}"]`);
                if (msgEl) {
                    const readStatus = msgEl.querySelector('.read-status');
                    if (readStatus && !msgEl.classList.contains('read')) {
                        readStatus.textContent = '✔️';
                        readStatus.style.color = '#999';
                    }
                }
            })
            .listen('.MessagesMarkedAsRead', function (e) {
    if (window.userId !== e.user_id) return;

    if (Array.isArray(e.message_ids)) {
        e.message_ids.forEach(id => {
            const msg = document.querySelector(`.message-sent[data-id="${id}"]`);
            if (msg) {
                const readStatus = msg.querySelector('.read-status');
                if (readStatus) {
                    readStatus.textContent = '✔️✔️';
                    readStatus.style.color = 'green';
                    msg.classList.add('read');
                    msg.dataset.read = '1';
                }
            }
        });
    } else {
        console.warn('❗ e.message_ids is not an array:', e.message_ids);
    }
});

    });
    </script> --}}




