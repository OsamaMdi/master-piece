@extends('layouts.merchants.app')

@section('content')

<!-- Page Header Title -->
<h2 class="page-title mb-4">🔔 Merchant Notifications</h2>

<!-- ✅ Filter + Delete All -->
<div class="review-filter-bar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <form method="GET" class="review-filter-form d-flex flex-wrap gap-3 m-0">
        <select name="priority" class="review-select" onchange="this.form.submit()">
            <option value="">All Priorities</option>
            <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
            <option value="important" {{ request('priority') == 'important' ? 'selected' : '' }}>Important</option>
        </select>
    </form>
</div>

<div class="filter-header">
    <form id="clearAllForm" method="POST" action="{{ route('merchant.notifications.clear') }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger force-right" onclick="return confirmClearAll(event)">
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
            <th>From</th>
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
            <td>{{ ucfirst(str_replace('_', ' ', $notification->type)) }}</td>
            <td>
                @php
                    $fromUser = \App\Models\User::find($notification->from_user_id);
                @endphp
                {{ $fromUser ? $fromUser->name : 'System' }}
            </td>
            <td>
                <span class="custom-status {{ $notification->priority }}">
                    {{ ucfirst($notification->priority) }}
                </span>
            </td>
            <td>
                <span class="custom-status {{ $notification->is_read ? 'read' : 'unread' }}">
                    {{ $notification->is_read ? 'Read' : 'Unread' }}
                </span>
            </td>
            <td>{{ $notification->created_at->format('Y-m-d') }}</td>
            <td class="text-center">
                @if($notification->url)
                <a href="{{ $notification->url }}" class="btn btn-sm btn-outline-primary" title="Go to">
                    <i class="fas fa-arrow-right"></i>
                </a>
                @endif
                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" style="display:inline-block;" class="delete-form">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    // Attach SweetAlert to delete buttons
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', confirmDelete);
        });
    });
</script>
@endpush
