@extends('layouts.block')

@section('content')

<div class="blocked-container">
    <div class="blocked-card">
        <h2 class="blocked-title">🚫 Your Account is Blocked</h2>

        <p class="blocked-message">
            Sorry, your account has been blocked.
        </p>

        @if (session('blocked_until'))
            <p class="blocked-until">
                ⏳ Your block will expire on:
                <strong>{{ \Carbon\Carbon::parse(session('blocked_until'))->format('d-m-Y H:i') }}</strong>
            </p>
        @endif

        @if (session('block_reason'))
            <div class="blocked-reason">
                <strong>Block Reason:</strong> {{ session('block_reason') }}
            </div>
        @endif

        <hr class="blocked-divider">

        <p class="blocked-contact-text">
            If you have any questions or concerns, feel free to contact us.
        </p>

        <a href="{{ route('contact') }}" class="blocked-contact-btn">
            Contact Us
        </a>
    </div>
</div>
@endsection
