@extends('layouts.admin.app')

@section('content')
<div class="card card-body">
    <h2 class="form-title mb-4">🌐 Website Review</h2>

    {{-- 👤 معلومات المستخدم --}}
    <div class="mb-4">
        <h5>👤 Reviewer Info</h5>
        <div class="d-flex align-items-center gap-3">
            @if($review->user->profile_picture)
                <img src="{{ asset('storage/' . $review->user->profile_picture) }}" width="64" height="64" class="rounded-circle">
            @else
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <strong>{{ $review->user->name }}</strong><br>
                <small>{{ $review->user->email }}</small>
            </div>
        </div>
    </div>

    {{-- 💬 نص التقييم --}}
    <div class="mb-4">
        <h5>⭐ Review Details</h5>
        <p><strong>Rating:</strong> {{ $review->rating }} / 5</p>
        <p><strong>Comment:</strong></p>
        <div class="p-3 border rounded bg-light">
            {{ $review->review_text }}
        </div>
    </div>

    <p class="text-muted mt-3"><strong>Submitted At:</strong> {{ $review->created_at->format('Y-m-d H:i') }}</p>

    <a href="{{ route('admin.website-reviews.index') }}" class="btn btn-secondary mt-4">← Back to Website Reviews</a>
</div>
@endsection
