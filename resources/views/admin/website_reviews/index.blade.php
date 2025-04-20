@extends('layouts.admin.app')

@section('content')
<h2 class="page-title mb-4">💬 Website Reviews</h2>

<form method="GET" class="d-flex gap-3 align-items-center mb-4 flex-wrap">
    <select name="filter" class="form-select" onchange="this.form.submit()">
        <option value="">-- All Reviews --</option>
        <option value="worst" {{ request('filter') === 'worst' ? 'selected' : '' }}>Worst Ratings (1-2)</option>
    </select>
</form>
@if($reviews->count())
<table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reviews as $review)
        <tr>
            <td>{{ $loop->iteration + ($reviews->currentPage() - 1) * $reviews->perPage() }}</td>

            {{-- 👤 اسم المستخدم فقط --}}
            <td>{{ $review->user->name }}</td>

            {{-- ⭐ التقييم --}}
            <td>{{ $review->rating }}/5</td>

            {{-- 💬 جزء من التعليق --}}
            <td>{{ Str::limit($review->review_text, 50) }}</td>

            {{-- 🔘 الإجراءات --}}
            <td class="text-center">
                <a href="{{ route('admin.website-reviews.show', $review->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.website-reviews.edit', $review->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.website-reviews.destroy', $review->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this review?')">
                    @csrf @method('DELETE')
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
