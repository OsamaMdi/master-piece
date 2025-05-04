@extends('layouts.merchants.app')

@section('content')

<h2 class="page-title mb-4">🚩 My Reports</h2>

<!-- Filter Section -->
<div class="review-filter-bar d-flex justify-content-end align-items-center mb-4">
    <form method="GET" class="d-flex flex-wrap gap-2 m-0">
        <select name="target_type" class="form-select form-select-sm">
            <option value="">All Types</option>
            <option value="reservation" {{ request('target_type') == 'reservation' ? 'selected' : '' }}>Reservation</option>
            <option value="review" {{ request('target_type') == 'review' ? 'selected' : '' }}>Review</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
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
        @php
            $statusClass = match($report->status) {
                'pending' => 'custom-status active',
                'reviewed' => 'custom-status review',
                'resolved' => 'custom-status blocked',
                default => 'custom-status unknown'
            };
        @endphp
        <tr>
            <td>{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
            <td>{{ ucfirst($report->target_type) }}</td>
            <td><span class="{{ $statusClass }}">{{ ucfirst($report->status) }}</span></td>
            <td>{{ $report->subject ?? '-' }}</td>
            <td>{{ Str::limit($report->message, 80) }}</td>
            <td>{{ $report->created_at->format('Y-m-d') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $reports->withQueryString()->links() }}
</div>
@else
<div class="alert alert-info">You haven’t submitted any reports yet.</div>
@endif

@endsection
