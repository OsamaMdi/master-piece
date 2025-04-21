@extends('layouts.admin.app')

@section('content')

<!-- Header -->
<div class="product-show-header mb-4">
    <h2 class="page-title">All Reservations for Your Products</h2>
    <p class="total-reservations">
        You have a total of {{ $reservations->total() }} reservation{{ $reservations->total() !== 1 ? 's' : '' }} across all your listed products.
    </p>
</div>

<!-- Reservations Table -->
@if($reservations->count())
<table class="table table-bordered">
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

            <!-- User Info -->
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

            <!-- Product Info + Owner -->
            <td>
                <div class="d-flex flex-column">
                    <span>{{ $reservation->product->name }}</span>
                    <small class="text-muted">by {{ $reservation->product->user->name ?? 'Unknown' }}</small>
                </div>
            </td>

            <!-- Dates -->
            <td>{{ \Carbon\Carbon::parse($reservation->start_date)->format('M d, Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($reservation->end_date)->format('M d, Y') }}</td>

            <!-- Status -->
            <td>
                <span class="badge bg-{{
                    $reservation->status === 'pending' ? 'secondary' :
                    ($reservation->status === 'approved' ? 'success' :
                    ($reservation->status === 'cancelled' ? 'danger' : 'warning text-dark'))
                }}">
                    {{ ucfirst($reservation->status) }}
                </span>
            </td>

            <!-- Actions -->
            <td class="text-center">
                <a href="{{ route('admin.reservation.details', $reservation->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i> View
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination -->
<div class="mt-4">
    {{ $reservations->links() }}
</div>
@else
    <div class="alert alert-info">You currently have no reservations for your products.</div>
@endif

@endsection
