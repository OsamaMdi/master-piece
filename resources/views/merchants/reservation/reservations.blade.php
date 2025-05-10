@extends('layouts.merchants.app')

@section('content')

<!-- Header -->
<div class="product-show-header mb-4">
    <h2 class="page-title">All Reservations for Your Products</h2>
    <p class="total-reservations">
        You have a total of {{ $reservations->total() }} reservation{{ $reservations->total() !== 1 ? 's' : '' }} across all your listed products.
    </p>
</div>

<!-- ✅ Unified Filter Row -->
<div class="review-filter-bar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <form method="GET" class="review-filter-form d-flex flex-wrap gap-3 m-0">
        <select name="status" class="review-select">
            <option value="">All Statuses</option>
            <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>Not Started</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <!-- ✅ Filter by Date -->
        <input type="date" name="from" class="review-select" value="{{ request('from') }}">
        <input type="date" name="to" class="review-select" value="{{ request('to') }}">
        <button type="submit" class="review-filter-btn">🔍 Filter</button>
    </form>
</div>

@if($reservations->count())
<table class="table table-bordered" style="margin-top: 20px">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Product</th>
            <th>From</th>
            <th>To</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reservations as $reservation)
        <tr>
            <td>{{ $loop->iteration + ($reservations->currentPage() - 1) * $reservations->perPage() }}</td>

            <td>
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ $reservation->user->profile_picture
                        ? asset('storage/' . $reservation->user->profile_picture)
                        : asset('img/default-user.png') }}"
                        alt="{{ $reservation->user->name }}"
                        class="product-img rounded-circle"
                        style="width: 40px; height: 40px; object-fit: cover;">
                    <span>{{ $reservation->user->name }}</span>
                </div>
            </td>

            <td>
                <div class="d-flex flex-column">
                    <span>
                        {{ $reservation->product->name }}
                        @if($reservation->reports->count() > 0)
                            <span title="This reservation has reports">🚩</span>
                        @endif
                    </span>

                </div>
            </td>

            <td>{{ \Carbon\Carbon::parse($reservation->start_date)->format('M d, Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($reservation->end_date)->format('M d, Y') }}</td>

            <td>
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
                <span class="{{ $statusClass }}">
                    {{ ucfirst(str_replace('_', ' ', $reservation->status ?? 'Unknown')) }}
                </span>
            </td>

            <td class="text-center">
                <a href="{{ route('merchant.reservation.details', $reservation->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i> View
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $reservations->links() }}
</div>
@else
    <div class="alert alert-info">You currently have no reservations for your products.</div>
@endif

@endsection
