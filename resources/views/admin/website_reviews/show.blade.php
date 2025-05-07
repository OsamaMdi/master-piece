@extends('layouts.admin.app')

@section('content')

<!-- ======== Page Title ======== -->
<div class="product-page-header">
    <h1 class="page-title">Website Review by "{{ $review->user->name }}"</h1>
</div>

<!-- ======== Review Details Card Layout ======== -->
<div class="product-card-container">
    <div class="product-details-card">

        <!-- Left: User Image -->
        <div class="product-image-side">
            <img src="{{ $review->user->profile_picture ? asset('storage/' . $review->user->profile_picture) : asset('img/default-user.png') }}"
                 alt="{{ $review->user->name }}"
                 class="img-fluid rounded-circle"
                 style="max-height: 250px; width: auto;">
        </div>

        <!-- Right: Review Info -->
        <div class="product-info-side">
            <h3>Reviewer:</h3>
            <p>{{ $review->user->name }}</p>

            <h3>Email:</h3>
            <p>{{ $review->user->email }}</p>

            <h3>Rating:</h3>
            <p>{{ $review->rating }} / 5</p>

            <h3>Comment:</h3>
            <div class="p-3 border rounded bg-light">
                {{ $review->review_text }}
            </div>

            <p class="text-muted mt-3"><strong>Submitted At:</strong> {{ $review->created_at->format('Y-m-d H:i') }}</p>

            <div class="mt-4 d-flex flex-wrap gap-2">
                <a href="{{ route('admin.website-reviews.index') }}" class="btn btn-secondary">
                    ← Back to Website Reviews
                </a>
                <a href="{{ route('admin.users.show', $review->user->id) }}" class="btn btn-outline-info">
                    👤 View User
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
