@extends('layouts.admin.app')

@section('content')
<div class="card card-body">
    <h2 class="form-title mb-4">➕ Add Website Review</h2>
    <form method="POST" action="{{ route('admin.website-reviews.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-col">
                <label class="form-label">User</label>
                <select name="user_id" class="form-select" required>
                    @foreach(App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-col">
                <label class="form-label">Rating</label>
                <input type="number" name="rating" class="form-control" min="1" max="5" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-col">
                <label class="form-label">Review Text</label>
                <textarea name="review_text" class="form-control" rows="3" required></textarea>
            </div>
        </div>
        <div class="mt-4 d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.website-reviews.index') }}" class="btn btn-secondary">← Back</a>
        </div>
    </form>
</div>
@endsection
