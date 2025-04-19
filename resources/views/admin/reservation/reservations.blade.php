@extends('layouts.admin.app')

@section('content')

<!-- Header -->
<div class="product-show-header">
    <h2 class="page-title">All Reservations for Your Products</h2>
    <p class="total-reservations">You have a total of {{ $reservations->total() }} reservation{{ $reservations->total() !== 1 ? 's' : '' }} across all your listed products.</p>
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
                <p><strong>Product:</strong> {{ $reservation->product->name }}</p>
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
        <p class="no-reservations-message">You currently have no reservations for your products.</p>
    @endforelse
</div>

<!-- Pagination -->
@if ($reservations->hasPages())
<div class="pagination-container">
    {{ $reservations->links() }}
</div>
@endif

@endsection
