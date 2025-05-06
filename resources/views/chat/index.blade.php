@extends(Auth::user()->user_type === 'merchant' ? 'layouts.merchants.app' : 'layouts.user.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">

<div class="chat-list-container">
    <h3 class="chat-list-title">💬 Conversations</h3>

    <div class="chat-list">
        @forelse ($chats as $chat)
        @php
        $currentUser = Auth::user();
        $isSender = $chat->sender_id === $currentUser->id && $chat->sender_type === get_class($currentUser);
        $otherParty = $isSender ? $chat->receiver : $chat->sender;
        $lastMessage = $chat->messages()->latest()->first();
        $profileImage = $otherParty->profile_picture
            ? asset('storage/' . $otherParty->profile_picture)
            : asset('img/default-user.png');

        $unreadFromOther = $chat->messages()
            ->where('read', false)
            ->where('sender_id', '!=', $currentUser->id)
            ->count();
    @endphp

<a href="{{ route('chat.show', $chat->id) }}" class="chat-item">
    <div class="chat-content">
        <img src="{{ $profileImage }}" class="chat-avatar" alt="{{ $otherParty->name }}">
        <div class="chat-info">
            <div class="chat-user">{{ $otherParty->name ?? 'User' }}</div>
            <div class="chat-preview">
                {{ $lastMessage?->message ? Str::limit($lastMessage->message, 40) : 'No messages yet' }}
            </div>
        </div>
    </div>
    @if($unreadFromOther > 0)
        <div class="chat-unread">{{ $unreadFromOther }}</div>
    @endif
</a>
        @empty
            <div class="chat-empty">No conversations found</div>
        @endforelse
    </div>
</div>
@endsection
