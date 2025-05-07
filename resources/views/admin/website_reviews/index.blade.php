@extends('layouts.admin.app')

@section('content')

<!-- Page Header Title -->
<h2 class="page-title mb-4">💬 Website Reviews</h2>

<!-- ✅ Custom Filter Row (معدل) -->
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




<!-- Add New Review Button -->
<div class="filter-header">
    <a href="{{ route('admin.website-reviews.create') }}" class="btn btn-success force-right">
        ➕ Add Website Review
    </a>
</div>

<!-- Website Reviews Table -->
@if($reviews->count())
<table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>User</th>
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
            <td>{{ $review->rating }}/5</td>
            <td>{{ Str::limit($review->review_text, 60) }}</td>
            <td class="text-center">
                <a href="{{ route('admin.website-reviews.show', $review->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                    <i class="fas fa-eye"></i>
                </a>
                {{-- <a href="{{ route('admin.website-reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="fas fa-edit"></i>
                </a> --}}
                <form action="{{ route('admin.website-reviews.destroy', $review->id) }}" method="POST" class="delete-form" style="display:inline-block;">
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
    <div class="alert alert-info">No website reviews found.</div>
@endif

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This website review will be permanently deleted.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
