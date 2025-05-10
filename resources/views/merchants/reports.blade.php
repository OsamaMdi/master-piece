@extends('layouts.merchants.app')

@section('content')
<h2 class="page-title mb-4">🚩 My Reports</h2>

<!-- ✅ Unified Filter Row -->
<div class="review-filter-bar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <form method="GET" class="review-filter-form d-flex flex-wrap gap-3 m-0">
        <select name="target_type" class="review-select">
            <option value="">All Types</option>
            <option value="review" {{ request('target_type') == 'review' ? 'selected' : '' }}>Review</option>
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

@if($reports->count())
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Type</th>
            <th>Status</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Submitted At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        <tr>
            <td>{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
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
                <span class="{{ $statusClass }}">{{ ucfirst($report->status ?? 'Unknown') }}</span>
            </td>
            <td>{{ $report->subject ?? '-' }}</td>
            <td>{{ \Illuminate\Support\Str::limit($report->message, 100) }}</td>
            <td>{{ $report->created_at->format('Y-m-d') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $reports->withQueryString()->links() }}
</div>
@else
<div class="alert alert-info">You haven't submitted any reports yet.</div>
@endif
@endsection
