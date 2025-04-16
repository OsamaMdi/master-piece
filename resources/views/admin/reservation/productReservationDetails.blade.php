@extends('layouts.merchants.app')

@section('content')

<div class="product-show-header">
    <h2 class="page-title">Reservation Details for {{ $reservation->product->name }}</h2>
</div>

<div class="reservation-detail-card-custom">

    {{-- User Information --}}
    <div class="user-info-detail-custom">
        <img
            src="{{ $reservation->user->profile_picture ? asset('storage/' . $reservation->user->profile_picture) : asset('img/default-user.png') }}"
            class="user-avatar-large-custom"
            alt="{{ $reservation->user->name }}"
        >
        <h3>{{ $reservation->user->name }}</h3>
        <p><strong>Address:</strong> {{ $reservation->user->address ?? 'Not Provided' }}</p>
    </div>

    {{-- Product Information --}}
    <div class="product-info-detail-custom">
        <div class="product-image-text-container">
            {{-- Product Image --}}
            <div class="product-image-wrapper">
                @if($reservation->product->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $reservation->product->images->first()->image_url) }}"
                         class="product-image-detail-custom"
                         alt="{{ $reservation->product->name }}">
                @else
                    <img src="{{ asset('images/default-product.png') }}"
                         class="product-image-detail-custom"
                         alt="Default Product Image">
                @endif
            </div>

            {{-- Product Text Content --}}
            <div class="product-text-content">
                <h3>{{ $reservation->product->name }}</h3>
                {{-- Reservation Information --}}
    <div class="reservation-info-detail-custom">
        <h4>Reservation Info:</h4>
        <p><strong>From:</strong> {{ \Carbon\Carbon::parse($reservation->start_date)->format('M d, Y') }}</p>
        <p><strong>To:</strong> {{ \Carbon\Carbon::parse($reservation->end_date)->format('M d, Y') }}</p>
        <p><strong>Status:</strong>
            <span class="status-badge status-{{ $reservation->status }}">
                {{ ucfirst($reservation->status) }}
            </span>

        </p>
        <p><strong>Reserved At:</strong> {{ \Carbon\Carbon::parse($reservation->created_at)->format('M d, Y H:i') }}</p>
    </div>

            </div>
        </div>
    </div>


    {{-- Number of Reservations --}}
    <div class="reservation-count-detail-custom">
        @php
            $reservationsCount = $reservation->product
                ->reservations()
                ->where('user_id', $reservation->user->id)
                ->count();
        @endphp
        <p><strong>Total times this user reserved this product:</strong> {{ $reservationsCount }}</p>
    </div>

    {{-- All Reviews by the User on This Product --}}
    <div class="user-reviews-on-product" style="margin-top: 40px;">
        <h3>All Reviews by {{ $reservation->user->name }} on {{ $reservation->product->name }}</h3>

        @if($reviews->isNotEmpty())
            <div class="reviews-list">
                @foreach($reviews as $review)
                    <div class="single-review" style="background: #f9f9f9; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                        <p class="rating-stars" style="font-size: 18px;">{{ str_repeat('⭐', $review->rating) }}</p>
                        <p>{{ $review->review_text }}</p>
                        <p style="font-size: 12px; color: #777;">
                            Written on: {{ $review->created_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p>This user has not written any reviews for this product yet.</p>
        @endif
    </div>

</div>

{{-- Cancel Reservation Button --}}
@if($reservation->status != 'cancelled' && now()->lt(\Carbon\Carbon::parse($reservation->start_date)) && \Carbon\Carbon::parse($reservation->start_date)->diffInHours(now()) > 48)
    <form action="{{ route('merchant.reservation.cancel', $reservation->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn-cancel-reservation">Cancel Reservation</button>
    </form>
@endif

{{-- Back Button --}}
<a href="{{ route('merchant.reservations', $reservation->product_id) }}" class="btn-back-fixed">
    🔙 Back
</a>

{{-- Success and Error Messages --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@endsection


