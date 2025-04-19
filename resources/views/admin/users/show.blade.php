@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <div class="card p-4">

        <!-- Header: Profile Picture + Name -->
        <div class="d-flex align-items-center mb-4" style="gap: 1.5rem;">
            @if($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" class="profile-image-preview" alt="Profile Picture" style="width: 80px; height: 80px;">
            @else
                <div class="profile-image-preview" style="width: 80px; height: 80px; background: #edf2f7; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #718096;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <h2 class="form-title m-0">{{ $user->name }}</h2>
        </div>

        <!-- User Info -->
        <div class="form-row">
            <div class="form-col">
                <p><strong>Email:</strong> {{ $user->email }}</p>
            </div>
            <div class="form-col">
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
            </div>
            <div class="form-col">
                <p><strong>Type:</strong> {{ ucfirst($user->user_type) }}</p>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <p><strong>Identity Number:</strong> {{ $user->identity_number }}</p>
            </div>
            <div class="form-col">
                <p><strong>City:</strong> {{ $user->city }}</p>
            </div>
        </div>

        @if($user->identity_image)
            <div class="mt-4">
                <p><strong>Identity Image:</strong></p>
                <div class="text-center">
                    <img src="{{ asset('storage/' . $user->identity_image) }}" alt="Identity Image"
                         style="max-width: 100%; max-height: 350px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid #ddd;">
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-4 text-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Back to Users</a>
        </div>
    </div>
</div>
@endsection
