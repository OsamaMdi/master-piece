@extends('layouts.admin.app')

@section('content')

<!-- Page Header Title -->
<h2 class="page-title mb-4">🛠️ Manage Products</h2>

<!-- ✅ Unified Filter Row -->
<div class="review-filter-bar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <form method="GET" class="review-filter-form d-flex flex-wrap gap-3 m-0">
        <input type="text" name="search" class="review-select review-search" placeholder="Search by product name..." value="{{ request('search') }}">
        <select name="sort" class="review-select">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
        </select>
        <button type="submit" class="review-filter-btn">🔍 Filter</button>
    </form>
</div>

<!-- Add Product Button -->
<div class="filter-header">
    <a id="openAddProductModal" href="javascript:void(0);" class="btn btn-success force-right">
        ➕ Add New Product
    </a>
</div>

<!-- Products Table -->
@if($products->count())
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Merchant</th>
            <th>Price (JOD/day)</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
            <td>
                @if($product->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $product->images->sortByDesc('created_at')->first()->image_url) }}" alt="{{ $product->name }}" class="product-img">
                @else
                    <img src="{{ asset('img/logo.png') }}" alt="No Image" class="product-img">
                @endif
            </td>

            <td>{{ $product->name }}</td>
            <td>{{ $product->user->name ?? 'N/A' }}</td>
            <td>{{ number_format($product->price, 2) }}</td>
            <td>{{ $product->quantity }}</td>
            @php
                    $statusClass = match($product->status) {
                        'available' => 'custom-status available',
                        'blocked' => 'custom-status blocked',
                        'maintenance' => 'custom-status maintenance',
                        default => 'custom-status unknown'
                    };
                @endphp
                <td>
                    <span class="{{ $statusClass }}">
                        {{ ucfirst($product->status ?? 'Unknown') }}
                    </span>
                </td>

            <td class="text-center">
                <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete(event)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination -->
<div class="mt-4">
    {{ $products->withQueryString()->links() }}
</div>
@else
    <div class="alert alert-info">No products found.</div>
@endif

<!-- ✅ Add Product Modal -->
<div id="addProductModal" class="modal {{ $errors->any() ? '' : 'hidden' }}">
    <div class="modal-content">
        <h3>Add New Product</h3>
        <form id="addProductForm" action="{{ route('admin.products.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                @error('name')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required>{{ old('description') }}</textarea>
                @error('description')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label>Price (JOD/day):</label>
                <input type="number" name="price" step="0.01" value="{{ old('price') }}" required>
                @error('price')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label>Quantity:</label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
                @error('quantity')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label>Category:</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="form-group">
                <label>Select Merchant:</label>
                <select name="merchant_id" required>
                    <option value="">Select Merchant</option>
                    @foreach($merchants as $merchant)
                        <option value="{{ $merchant->id }}" {{ old('merchant_id') == $merchant->id ? 'selected' : '' }}>
                            {{ $merchant->name }} ({{ $merchant->email }})
                        </option>
                    @endforeach
                </select>
                @error('merchant_id')<small class="error-text text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="modal-actions">
                <button type="button" id="cancelAddProduct" class="btn btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-add">Add Product</button>
            </div>
        </form>
    </div>
</div>

<div id="imagePreviewModal" class="modal hidden" style="...">
    <img id="previewImage" src="" style="...">
</div>

<!-- ✅ SweetAlert Notifications -->
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}",
    });
</script>
@endif

<!-- ✅ Custom JS -->
<script>

    document.getElementById('openAddProductModal').addEventListener('click', function () {
        document.getElementById('addProductModal').classList.remove('hidden');
    });

    // إغلاق المودال
    document.getElementById('cancelAddProduct').addEventListener('click', function () {
        document.getElementById('addProductModal').classList.add('hidden');
    });

    // تأكيد الحذف
    function confirmDelete(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete this product.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.submit();
            }
        });
    }
</script>

@endsection
