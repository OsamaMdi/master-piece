@extends('layouts.admin.app')

@section('content')
<div class="container mt-5">
    <div class="profile-card">

        <!-- Header inside card: Profile Picture + Title -->
        <div class="custom-grid gap-2">
            <div class="flex-shrink-0">
                <img
                src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('img/default-user.png') }}"
                class="rounded-circle"
                style="width: 90px; height: 70px;">
            </div>
            <div class="flex-grow-1">
                <h2 class="m-0">✏️ Edit User</h2>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form id="editUserForm" action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-col">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-col">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-col">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="blocked" {{ old('status', $user->status) == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="user_type" class="form-label">User Type</label>
                    <select name="user_type" id="user_type" class="form-select" required>
                        <option value="user" {{ old('user_type', $user->user_type) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="merchant" {{ old('user_type', $user->user_type) == 'merchant' ? 'selected' : '' }}>Merchant</option>
                        <option value="admin" {{ old('user_type', $user->user_type) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('user_type') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-col">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                    @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="form-row">


                <div class="form-col">
                    <label for="profile_picture" class="form-label">Profile Picture (optional)</label>
                    <input type="file" name="profile_picture" class="form-control" accept="image/*">
                    @error('profile_picture') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-col">
                    <label for="identity_image" class="form-label">Identity Image (optional)</label>
                    <input type="file" name="identity_image" class="form-control" id="identity_image">
                    @error('identity_image') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>


@endsection

