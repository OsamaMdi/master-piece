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

                @php
                    $statusClass = match($reservation->status) {
                        'not_started' => 'custom-status not-started',
                        'in_progress' => 'custom-status in-progress',
                        'completed' => 'custom-status completed',
                        'cancelled' => 'custom-status cancelled',
                        'reported' => 'custom-status reported',
                        default => 'custom-status unknown'
                    };
                @endphp

                <div class="info-row">
                    <h3>Status:</h3>
                    <p class="{{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $reservation->status ?? 'Unknown')) }}
                    </p>
                </div>

                <p><strong>Reserved At:</strong> {{ \Carbon\Carbon::parse($reservation->created_at)->format('M d, Y H:i') }}</p>

                <p><strong>Reservation Type:</strong> {{ ucfirst(str_replace('_', ' ', $reservation->reservation_type)) }}</p>

                <p><strong>Total Price:</strong> {{ number_format($reservation->total_price, 2) }} JOD</p>

                <p><strong>Paid Amount:</strong>
                    @if($reservation->paid_amount !== null)
                        {{ number_format($reservation->paid_amount, 2) }} JOD
                    @else
                        Not Paid
                    @endif
                </p>

                <p><strong>Platform Fee (5%):</strong>
                    @if($reservation->platform_fee !== null)
                        {{ number_format($reservation->platform_fee, 2) }} JOD
                    @else
                        Not Calculated
                    @endif
                </p>

                <p><strong>Reservation Slug:</strong> {{ $reservation->slug }}</p>

                @if($reservation->comment)
                    <p><strong>Comment:</strong> {{ $reservation->comment }}</p>
                @endif
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

{{-- Cancel Reservation Button --}}
@php
    $startDate = \Carbon\Carbon::parse($reservation->start_date);
    $hoursUntilStart = now()->diffInHours($startDate, false);
@endphp

@if($reservation->status != 'cancelled' && $hoursUntilStart > 48)
    <button type="button" class="btn-cancel-reservation" id="cancelReservationButton">Cancel Reservation</button>

    <form id="cancelReservationForm" action="{{ route('merchant.reservation.cancel', $reservation->id) }}" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>
@endif

@php
    $report = $reservation->reports()->where('status', 'pending')->first();
@endphp
<a href="javascript:history.back()" class="btn btn-secondary">← Back</a>
@if (!$report)
    <!-- Button to open the report modal -->
    <button type="button"
    class="btn btn-warning openReportModalBtn"
    data-reportable-type="{{ get_class($reservation) }}"
    data-reportable-id="{{ $reservation->id }}"
    data-target-type="reservation"
    data-report-url="{{ route('reports.send') }}">
    🚩 Report
</button>
@else
    <!-- Button to resolve the report -->
    <form action="{{ route('reports.resolve', $report->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-success">
            ✔️ Resolve Report
        </button>
    </form>
@endif

@if($report)
    <span class="badge badge-danger">🚩 Reported</span>
@endif

</div>


{{-- Back Button --}}
{{-- <a href="{{ route('merchant.reservations', $reservation->product_id) }}" class="btn-back-fixed">
    🔙 Back
</a>
 --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cancelBtn = document.getElementById('cancelReservationButton');
    const cancelForm = document.getElementById('cancelReservationForm');

    if (cancelBtn) {
        cancelBtn.addEventListener('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to cancel this reservation?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    cancelForm.submit();
                }
            });
        });
    }
});
</script>


<script src="{{ asset('js/poppReport.js') }}"></script>
@endsection


