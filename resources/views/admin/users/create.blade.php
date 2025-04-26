@extends('layouts.admin.app')

@section('content')
<style>
    .swal2-container {
        z-index: 9999 !important;
    }
</style>

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        timer: 5000,
        showConfirmButton: false
    });
</script>
@endif

<div class="card card-body">
    <div class="d-flex align-items-center gap-3 mb-4">
        <h2 class="form-title m-0">➕ Create New User</h2>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" id="createUserForm">
        @csrf

        <div class="form-row">
            <div class="form-col">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="form-col">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="form-col">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    <option value="under_review" {{ old('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                </select>
                @error('status')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="form-col">
                <label class="form-label">User Type</label>
                <select name="user_type" class="form-select" required>
                    <option value="user" {{ old('user_type') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="merchant" {{ old('user_type') == 'merchant' ? 'selected' : '' }}>Merchant</option>
                    <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('user_type')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <label class="form-label">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control" accept="image/*">
                @error('profile_picture')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="form-col">
                <label class="form-label">Identity Image</label>
                <input type="file" id="identity_image" name="identity_image" class="form-control" accept="image/*">
                @error('identity_image')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>

        <input type="hidden" name="identity_number" value="{{ old('identity_number') }}">
        <input type="hidden" name="city" value="{{ old('city') }}">


        <div class="mt-4 d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Save User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Back to Users</a>
        </div>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
