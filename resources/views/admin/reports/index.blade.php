@extends('layouts.admin.app')

@section('content')

<!-- Page Header Title -->
<h2 class="page-title mb-4">🚩 Manage Reports</h2>

<!-- ✅ Unified Filter Row with Target Type and Status Filters -->
<div class="review-filter-bar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <form method="GET" class="review-filter-form d-flex flex-wrap gap-3 m-0">
        <input type="text" name="search" class="review-select review-search" placeholder="Search by reporter name..." value="{{ request('search') }}">

        <select name="target_type" class="review-select">
            <option value="">All Types</option>
            <option value="product" {{ request('target_type') == 'product' ? 'selected' : '' }}>Product</option>
            <option value="review" {{ request('target_type') == 'review' ? 'selected' : '' }}>Review</option>
            <option value="general" {{ request('target_type') == 'general' ? 'selected' : '' }}>General</option>
            <option value="reservation" {{ request('target_type') == 'reservation' ? 'selected' : '' }}>Reservation</option>
        </select>

        <select name="status" class="review-select">
            <option value="">All Statuses</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
        </select>

        <button type="submit" class="review-filter-btn">🔍 Filter</button>
    </form>
</div>

<!-- Reports Table -->
@if($reports->count())
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Reporter</th>
            <th>Type</th>
            <th>Status</th>
            <th>Submitted At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        <tr>
            <td>{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
            <td>{{ $report->user->name ?? 'Unknown' }}</td>
            <td>{{ ucfirst($report->target_type) }}</td>
            @php
                $statusClass = match($report->status) {
                    'pending' => 'custom-status active',
                    'reviewed' => 'custom-status review',
                    'resolved' => 'custom-status blocked',
                    default => 'custom-status unknown'
                };
            @endphp
            <td>
                <span class="{{ $statusClass }}">
                    {{ ucfirst($report->status ?? 'Unknown') }}
                </span>
            </td>
            <td>{{ $report->created_at->format('Y-m-d') }}</td>
            <td class="text-center">
                <a href="{{ route('admin.reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                    <i class="fas fa-eye"></i>
                </a>

                <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination -->
<div class="mt-4">
    {{ $reports->withQueryString()->links() }}
</div>
@else
    <div class="alert alert-info">No reports found.</div>
@endif

@endsection
