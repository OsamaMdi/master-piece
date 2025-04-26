@extends('layouts.block')

@section('content')
<div class="blocked-container">
    <div class="blocked-card">
        <h2 class="blocked-title">⏳ Your Account is Under Review</h2>

        <p class="blocked-message">
            Thank you for registering as a merchant.
            Your account is currently under review by our team.
        </p>

        <p class="blocked-contact-text">
            Once your account is approved, you will be able to access your dashboard and start managing your products.
        </p>

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
