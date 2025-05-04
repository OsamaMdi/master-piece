@extends('layouts.merchants.app')

@section('content')

<!-- Page Header Title -->
<h2 class="page-title mb-4">🔔 Merchant Notifications</h2>

<!-- ✅ Filter + Delete All -->
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form method="GET" class="d-flex gap-2 flex-wrap m-0">
        <select name="priority" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All Priorities</option>
            <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
            <option value="important" {{ request('priority') == 'important' ? 'selected' : '' }}>Important</option>
        </select>
    </form>

    <form id="clearAllForm" method="POST" action="{{ route('merchant.notifications.clear') }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmClearAll(event)">
            <i class="fas fa-trash-alt me-1"></i> Clear All Notifications
        </button>
    </form>
</div>

@if($notifications->count())
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Message</th>
            <th>Type</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($notifications as $notification)
        <tr>
            <td>{{ $loop->iteration + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
            <td class="fw-semibold">{{ $notification->message }}</td>
            <td>{{ ucfirst($notification->type) }}</td>
            @php
                $priorityClass = 'custom-status ' . $notification->priority;
            @endphp
            <td>
                <span class="{{ $priorityClass }}">
                    {{ ucfirst($notification->priority) }}
                </span>
            </td>
            @php
                $readClass = $notification->is_read ? 'custom-status read' : 'custom-status unread';
            @endphp
            <td>
                <span class="{{ $readClass }}">
                    {{ $notification->is_read ? 'Read' : 'Unread' }}
                </span>
            </td>
            <td>{{ $notification->created_at->format('Y-m-d') }}</td>
            <td class="text-center">
                @if($notification->url)
                <a href="{{ $notification->url }}" class="btn btn-sm btn-outline-info" title="Go to">
                    <i class="fas fa-arrow-right"></i>
                </a>
                @endif

                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event)">
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
    {{ $notifications->withQueryString()->links() }}
</div>
@else
<div class="alert alert-info">No notifications found.</div>
@endif

@endsection

@push('scripts')
<script>
    function confirmDelete(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'This notification will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('form').submit();
            }
        });
        return false;
    }

    function confirmClearAll(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Clear All Notifications?',
            text: 'This will delete all your notifications permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, clear all!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('clearAllForm').submit();
            }
        });
        return false;
    }
</script>
@endpush
