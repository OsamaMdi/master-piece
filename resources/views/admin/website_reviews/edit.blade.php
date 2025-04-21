@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/create-edit-shared.css') }}">

<div class="create-user-section">
    <div class="form-block">
        <h2>✏️ Edit Website Review</h2>

        <form method="POST" action="{{ route('admin.website-reviews.update', $review->id) }}">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div>
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select" required>
                        @foreach(App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ $review->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div>
                    <label class="form-label">Rating</label>
                    <input type="number" name="rating" class="form-control" value="{{ $review->rating }}" min="1" max="5" required>
                    @error('rating')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label class="form-label">Review Text</label>
                    <textarea name="review_text" class="form-control" rows="4" required>{{ $review->review_text }}</textarea>
                    @error('review_text')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Update Review</button>
                <a href="{{ route('admin.website-reviews.index') }}" class="btn btn-secondary">← Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
