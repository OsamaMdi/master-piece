@extends('layouts.admin.app')

@section('content')

<!-- Page Header Title -->
<h2 class="page-title mb-4">⭐ Product Reviews</h2>

<!-- Custom Filter Row -->
<div class="review-filter-bar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <form method="GET" class="review-filter-form d-flex flex-wrap gap-3 m-0">
        <select name="filter" class="review-select">
            <option value="">-- All Reviews --</option>
            <option value="worst" {{ request('filter') === 'worst' ? 'selected' : '' }}>Worst Ratings (1-2)</option>
            <option value="best" {{ request('filter') === 'best' ? 'selected' : '' }}>Best Ratings (4-5)</option>
        </select>

        <select name="sort" class="review-select">
            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest First</option>
            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
        </select>

        <button type="submit" class="review-filter-btn">🔍 Filter</button>
    </form>
</div>

<!-- Add New Review Button on the right -->
<div class="filter-header">
    <a href="{{ route('admin.reviews.create') }}" class="btn btn-success force-right">
        ➕ Add New Review
    </a>
</div>

<!-- Table -->
@if($reviews->count())
<table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Product</th>
            <th>Owner</th>
            <th>Rating</th>
            <th>Review</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reviews as $review)
        <tr>
            <td>{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>
            <td>{{ $review->user->name }}</td>
            <td>{{ $review->product->name }}</td>
            <td>{{ $review->product->user->name }}</td>
            <td>{{ $review->rating }}/5</td>
            <td>{{ Str::limit($review->review_text, 50) }}</td>
            <td class="text-center">
                <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                    <i class="fas fa-eye"></i>
                </a>
                {{-- <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="fas fa-edit"></i>
                </a> --}}
                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this review?')">
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
    {{ $reviews->withQueryString()->links() }}
</div>
@else
    <div class="alert alert-info"> No product reviews found.</div>
@endif

@endsection
