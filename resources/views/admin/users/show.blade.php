@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <div class="card p-4">

<div class="custom-grid gap-2">
    <div class="flex-shrink-0">
        <img
        src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('img/default-user.png') }}"
        class="rounded-circle"
        style="width: 90px; height: 70px;">

    </div>
    <div class="flex-grow-1 mar">
        <h2 class="m-0">{{ $user->name }}</h2>
    </div>
</div>


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

        <!-- صورة الهوية -->
        @if($user->identity_image)
            <div class="mt-4">
                <p><strong>Identity Image:</strong></p>
                <div class="identity-image-wrapper">
                    <img src="{{ asset('storage/' . $user->identity_image) }}"
                         alt="Identity Image"
                         class="img-fluid identity-image">
                </div>
            </div>
        @endif


        <div class="mt-4 text-end" style="margin-top: 2rem;">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Back to Users</a>
        </div>
    </div>
</div>
<!-- ========== User Reviews Section ========== -->

<h4 class="mb-3">⭐ Latest Reviews by {{ strtoupper($user->name) }}</h4>

<!-- Filter Dropdown -->
<form method="GET" class="mb-4 d-flex align-items-center gap-2 flex-wrap">
    <select name="filter" class="form-select w-auto" onchange="this.form.submit()">
        <option value="">All Reviews</option>
        <option value="product" {{ request('filter') === 'product' ? 'selected' : '' }}>Product Reviews</option>
        <option value="website" {{ request('filter') === 'website' ? 'selected' : '' }}>Website Reviews</option>
    </select>
</form>

<!-- Reviews Cards -->
<div class="reviews-list">
    @if($filter !== 'website')
        @foreach($productReviews as $review)
            <div class="custom-review-card">
                <div class="custom-review-header">🛒 Product Review</div>
                <div class="custom-review-row">
                    <div class="equal-col">{!! str_repeat('⭐', $review->rating) !!}</div>
                    <div class="equal-col">{{ $review->review_text }}</div>
                    <div class="equal-col text-end">{{ $review->created_at->format('Y-m-d') }}</div>
                </div>
                @if($review->product)
                    <div class="text-muted mt-1" style="font-size: 0.875rem;">
                        <i class="fas fa-box"></i> Product: <strong>{{ $review->product->name }}</strong>
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    @if($filter !== 'product')
        @foreach($websiteReviews as $review)
            <div class="custom-review-card">
                <div class="custom-review-header">🌐 Website Review</div>
                <div class="custom-review-row">
                    <div class="equal-col">{!! str_repeat('⭐', $review->rating) !!}</div>
                    <div class="equal-col">{{ $review->review_text }}</div>
                    <div class="equal-col text-end">{{ $review->created_at->format('Y-m-d') }}</div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Pagination -->
@if($totalReviewsCount >= 10)
    <div class="mt-4 d-flex justify-content-center gap-4 flex-wrap">
        @if($filter !== 'website')
            {{ $productReviews->appends(['filter' => $filter])->links() }}
        @endif

        @if($filter !== 'product')
            {{ $websiteReviews->appends(['filter' => $filter])->links() }}
        @endif
    </div>
@endif

@endsection
