@extends('layouts.admin.app')

@section('content')
<div class="card card-body">
    <h2 class="form-title mb-4">📄 Category Details</h2>

    <div class="form-row">
        <div class="form-col">
            <p><strong>Name:</strong> {{ $category->name }}</p>
        </div>
        <div class="form-col">
            <p><strong>Description:</strong> {{ $category->description ?? '—' }}</p>
        </div>
    </div>

    <p><strong>Created At:</strong> {{ $category->created_at->format('Y-m-d H:i') }}</p>

    <div class="mt-4 text-end">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">← Back to Categories</a>
    </div>
</div>
@endsection
