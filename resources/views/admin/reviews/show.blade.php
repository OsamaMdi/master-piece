@extends('layouts.admin.app')

@section('content')

<!-- ======== Page Title ======== -->
<div class="product-page-header">
    <h1 class="page-title">Review for "{{ $review->product->name }}"</h1>
</div>

<!-- ======== Review Details Card Layout ======== -->
<div class="product-card-container">
    <div class="product-details-card">

        <!-- Left: Product Image -->
        <div class="product-image-side">
            @php
                $mainImage = $review->product->images->sortByDesc('created_at')->first();
            @endphp
<img src="{{ $mainImage ? asset('storage/' . $mainImage->image_url) : asset('img/logo.png') }}"
alt="{{ $review->product->name }}" class="img-fluid rounded" style="width: 60%;">

        </div>

        <!-- Right: Review Info -->
        <div class="product-info-side">
            <h3>Product:</h3>
            <p>{{ $review->product->name }}</p>

            <h3>Product Owner:</h3>
            <p>{{ $review->product->user->name }} ({{ $review->product->user->email }})</p>

            <h3>Reviewer:</h3>
            <p>{{ $review->user->name }} ({{ $review->user->email }})</p>

            <h3>Rating:</h3>
            <p>{{ $review->rating }} / 5</p>

            <h3>Review:</h3>
            <div class="p-3 border rounded bg-light">
                {{ $review->review_text }}
            </div>

            <p class="text-muted mt-3"><strong>Submitted At:</strong> {{ $review->created_at->format('Y-m-d H:i') }}</p>

            <!-- Action Buttons -->
            <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="{{ route('admin.products.show', $review->product->id) }}" class="btn btn-outline-primary" target="_blank">
                    🛍️ View Product
                </a>
                <a href="{{ route('admin.users.show',  $review->user->id) }}" class="btn btn-outline-info">
                    👤 View Reviewer
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary" style="margin-top:30px;">
                    ← Back to Reviews
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
