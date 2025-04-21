@extends('layouts.admin.app')

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
    <div class="user-reviews-on-product mt-5">
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

    {{-- Back and Disable Buttons --}}
    @if($reservation->product->status !== 'maintenance')

        <a href="{{ route('admin.reservations', $reservation->product_id) }}" class="btn btn-secondary mt-4">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    @endif
</div>

{{-- SweetAlert + Session Messages --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
    });
</script>
@endif

{{-- SweetAlert Confirmation for Disable --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const confirmBtn = document.getElementById('confirmDisableBtn');
        const disableForm = document.getElementById('disableProductForm');

        if (confirmBtn && disableForm) {
            confirmBtn.addEventListener('click', function () {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will disable the product and cancel all upcoming reservations.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, disable it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        disableForm.submit();
                    }
                });
            });
        }
    });
</script>

@endsection
