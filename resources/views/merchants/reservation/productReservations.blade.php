@extends('layouts.merchants.app')

@section('content')

<!-- Product Header -->
<div class="product-show-header">
    <h2 class="page-title">Reservations for: {{ $product->name }}</h2>
    <p class="total-reservations">Total Reservations: {{ $reservations->total() }}</p>
</div>

<!-- Reservations Grid -->
<div class="reservations-grid-custom">
    @forelse($reservations as $reservation)
        <div class="reservation-card-custom {{ $reservation->status }}">
            <!-- User Info -->
            <div class="user-info-custom">
                <img
                    src="{{ $reservation->user->profile_picture ? asset('storage/' . $reservation->user->profile_picture) : asset('img/default-user.png') }}"
                    class="user-avatar-custom"
                    alt="{{ $reservation->user->name }}">
                <div class="user-name-custom">{{ $reservation->user->name }}</div>
            </div>

            <!-- Reservation Info -->
            <div class="reservation-info-custom">
                <p><strong>From:</strong> {{ \Carbon\Carbon::parse($reservation->start_date)->format('M d, Y') }}</p>
                <p><strong>To:</strong> {{ \Carbon\Carbon::parse($reservation->end_date)->format('M d, Y') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($reservation->status) }}</p>
            </div>

           

            <!-- View Details Button -->
            <a href="{{ route('merchant.reservation.details', $reservation->id) }}" class="btn-view-details">
                View Details
            </a>
        </div>
    @empty
        <!-- No Reservations Message -->
        <p class="no-reservations-message">No reservations found.</p>
    @endforelse
</div>

<!-- Pagination -->
@if ($reservations->hasPages())
<div class="pagination-container">
    {{ $reservations->links() }}
</div>
@endif

<!-- Back Button (same class as show page) -->
<a href="{{ route('merchant.products.index') }}" class="btn-back-fixed">
    🔙 Back
</a>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom-product-reservation.css') }}">
@endpush
