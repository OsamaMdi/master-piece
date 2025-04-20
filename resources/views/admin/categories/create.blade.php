@extends('layouts.admin.app')

@section('content')
<div class="card card-body">
    <h2 class="form-title mb-4">➕ Add New Category</h2>

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-col">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-col">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Save Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">← Back</a>
        </div>
    </form>
</div>
@endsection
