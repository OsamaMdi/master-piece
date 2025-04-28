@extends('layouts.admin.app')

@section('content')
<style>
    .modal-content {
        margin: auto;
    }

.input-group > .form-select {
    margin-right: 8px;
}

</style>

<div class="container py-4">
    <div class="card p-4">

        <!-- Header -->
        <div class="custom-grid gap-2">
            <div class="flex-grow-1 mar">
                <h2 class="m-0">🚩 View Report</h2>
            </div>
        </div>

        <!-- Reporter Info -->
        <div class="form-row mt-4">
            <div class="form-col">
                <p><strong>Reporter Name:</strong> {{ $report->user->name ?? 'Unknown' }}</p>
            </div>
            <div class="form-col">
                <p><strong>Reporter Email:</strong> {{ $report->user->email ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Report Type and Status -->
        <div class="form-row">
            <div class="form-col">
                <p><strong>Report Type:</strong> {{ ucfirst($report->target_type) }}</p>
            </div>
            <div class="form-col">
                <p><strong>Status:</strong> {{ ucfirst($report->status) }}</p>
            </div>
        </div>

        <!-- Report Subject and Message -->
        <div class="form-row">
            <div class="form-col">
                <p><strong>Subject:</strong> {{ $report->subject ?? 'No subject provided' }}</p>
            </div>
            <div class="form-col">
                <p><strong>Message:</strong> {{ $report->message }}</p>
            </div>
        </div>

        <!-- Related Target Info -->
        @if($target)
            <div class="form-row mt-4">
                <div class="form-col">
                    @if($report->target_type === 'product')
                        <p><strong>Related Product:</strong> {{ $target->name ?? 'Unknown Product' }}</p>
                        <a href="{{ route('admin.products.show', $target->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                            View Product
                        </a>
                    @elseif($report->target_type === 'review')
                        <p><strong>Related Review:</strong> {{ Str::limit($target->review_text, 100) ?? 'No review text.' }}</p>
                        @if($target->product)
                            <p><strong>On Product:</strong> {{ $target->product->name ?? 'Unknown' }}</p>
                            <a href="{{ route('admin.products.show', $target->product->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                View Product
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        @endif

        <!-- Update Status Form (separate section) -->
        <div class="mt-4 text-end" style="margin-top: 2rem;">
            <form method="POST" action="{{ route('admin.reports.updateStatus', $report->id) }}" class="d-inline-flex align-items-center gap-2">
                @csrf
                @method('PATCH')

                <select name="status" class="form-select me-2" required style="width: 10vw;">
                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>

                <button type="submit" class="btn btn-success" >
                    ✅ Update
                </button>
            </form>

            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary ms-2" style="margin-top: 20px;">
                ← Back to Reports
            </a>
        </div>

    </div>
</div>
@endsection
