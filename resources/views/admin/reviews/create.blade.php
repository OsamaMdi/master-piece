@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/create-edit-shared.css') }}">

<div class="create-user-section">
    <div class="form-block">
        <h2>➕ Add Product Review</h2>

        <form method="POST" action="{{ route('admin.reviews.store') }}">
            @csrf

            <div class="form-row">
                <div>
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select" required>
                        @foreach(App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div>
                    <label class="form-label">Product</label>
                    <select name="product_id" class="form-select" required>
                        @foreach(App\Models\Product::all() as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    @error('product_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label class="form-label">Rating</label>
                    <input type="number" name="rating" class="form-control" min="1" max="5" required>
                    @error('rating')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div>
                    <label class="form-label">Review Text</label>
                    <textarea name="review_text" class="form-control" rows="4" required>{{ old('review_text') }}</textarea>
                    @error('review_text')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Save Review</button>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">← Back to Reviews</a>
            </div>
        </form>
    </div>
</div>
@endsection
