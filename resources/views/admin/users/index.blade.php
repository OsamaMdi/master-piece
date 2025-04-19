@extends('layouts.admin.app')

@section('content')

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title">👥 Manage Users</h2>

    <a href="{{ route('admin.users.create') }}" class="btn btn-success">
        ➕ Add New User
    </a>
</div>

<!-- Search & Filter -->
<form method="GET" class="search-filter-form mb-4 d-flex gap-3 flex-wrap">
    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">

    <select name="sort" class="form-select">
        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
    </select>

    <button type="submit" class="btn btn-primary">🔍 Filter</button>
</form>

<!-- Users Table -->
@if($users->count())
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Identity #</th> <!-- ✅ New Column -->
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
            <td>{{ $user->identity_number ?? '—' }}</td> <!-- ✅ New Cell -->
            <td>
                <span class="badge bg-{{ $user->status === 'active' ? 'success' : ($user->status === 'blocked' ? 'danger' : 'warning text-dark') }}">
                    {{ ucfirst($user->status) }}
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

<!-- SweetAlert Notification -->
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
    });
</script>
@endif

@endsection
