@extends('layouts.admin.app')

@section('content')
<h2 class="page-title mb-4">📂 Manage Categories</h2>

<!-- Search + Add (with unified filter classes) -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <form method="GET" class="review-filter-form d-flex flex-wrap gap-3 m-0">
        <input type="text" name="search" class="review-select review-search" placeholder="Search by name..." value="{{ request('search') }}">
        <button type="submit" class="review-filter-btn">🔍 Filter</button>
    </form>
</div>


</div><div class="filter-header">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success force-right" style="margin-top: 15px;">
        ➕ Add New Category
    </a>
</div>
</div>

@if($categories->count())
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Description</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
            <td>

                <span style="display: inline-block; background-color: {{ $category->color }}; padding: 5px 10px; border-radius: 50%; margin-right: 10px;">
                    <i class="{{ $category->icon }}" style="color: white; font-size: 14px;"></i>
                </span>
                {{ $category->name }}
            </td>
            <td>{{ $category->description ?? '—' }}</td>
            <td>{{ $category->created_at->format('Y-m-d') }}</td>
            <td class="text-center">
                <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $categories->withQueryString()->links() }}
</div>
@else
    <div class="alert alert-info">No categories found.</div>
@endif
@endsection
