@extends('layouts.admin.app')

@section('content')


<div class="container py-4">
    <div class="card p-4">

        <!-- Header -->
        <div class="custom-grid gap-2">
            <div class="flex-shrink-0">
                <img
                    src="{{ $review->user->profile_picture ? asset('storage/' . $review->user->profile_picture) : asset('img/default-user.png') }}"
                    class="rounded-circle"
                    style="width: 90px; height: 70px;">
            </div>
            <div class="flex-grow-1">
                <h2 class="m-0">🌐 Website Review</h2>
            </div>
        </div>

        <!-- Reviewer Info -->
        <div class="form-row mt-4">
            <div class="form-col">
                <p><strong>Reviewer:</strong> {{ $review->user->name }}</p>
            </div>
            <div class="form-col">
                <p><strong>Email:</strong> {{ $review->user->email }}</p>
            </div>
        </div>

        <div class="mt-2">
            <a href="{{ route('admin.users.show',  $review->user->id) }}" class="btn btn-sm btn-outline-info d-flex align-items-center gap-1">
                <i class="fas fa-user"></i> View User
            </a>
        </div>

        <!-- Review Content -->
        <div class="mt-4">
            <p><strong>Rating:</strong> {{ $review->rating }} / 5</p>
            <p><strong>Comment:</strong></p>
            <div class="p-3 border rounded bg-light">
                {{ $review->review_text }}
            </div>
        </div>

        <p class="text-muted mt-3"><strong>Submitted At:</strong> {{ $review->created_at->format('Y-m-d H:i') }}</p>

        <a href="{{ route('admin.website-reviews.index') }}" class="btn btn-secondary mt-4">← Back to Website Reviews</a>
    </div>
</div>
@endsection
