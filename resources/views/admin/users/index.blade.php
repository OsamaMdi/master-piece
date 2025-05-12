@extends('layouts.admin.app')

@section('content')

<!-- Page Header Title -->
<h2 class="page-title mb-4">👥 Manage Users</h2>
<!-- ✅ Unified Filter Row with Status, City, and User Type Filters -->
<div class="review-filter-bar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <form method="GET" class="review-filter-form d-flex flex-wrap gap-3 m-0">
        <input type="text" name="search" class="review-select review-search" placeholder="Search by name or email..." value="{{ request('search') }}">

        <select name="status" class="review-select">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Inactive</option>
        </select>

        <select name="user_type" class="review-select">
            <option value="">All User Types</option>
            <option value="user" {{ request('user_type') == 'user' ? 'selected' : '' }}>User</option>
            <option value="merchant" {{ request('user_type') == 'merchant' ? 'selected' : '' }}>Merchant</option>
            <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>

        <button type="submit" class="review-filter-btn">🔍 Filter</button>
    </form>
</div>



<!-- Add New User Button on the right -->
<div class="filter-header">
    <a href="{{ route('admin.users.create') }}" class="btn btn-success force-right">
        ➕ Add New User
    </a>
</div>


<!-- Users Table -->
@if($users->count())
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>User Type </th>
            <th>Status</th>
            <th>Joined</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?? '—' }}</td>
            <td>{{ $user->user_type  ?? '—' }}</td>
            @php
            $statusClass = match($user->status) {
                'active' => 'custom-status active',
                'blocked' => 'custom-status blocked',
                'under_review' => 'custom-status review',
                default => 'custom-status unknown'
            };
        @endphp
        <td>
            <span class="{{ $statusClass }}">
                {{ ucfirst($user->status ?? 'Unknown') }}
            </span>
        </td>
            <td>{{ $user->created_at->format('Y-m-d') }}</td>
            <td class="text-center">
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                    <i class="fas fa-eye"></i>
                </a>

                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>

                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event)">
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
    {{ $users->withQueryString()->links() }}
</div>
@else
    <div class="alert alert-info">No users found.</div>
@endif


@endsection
