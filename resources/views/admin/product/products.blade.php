@extends('layouts.merchants.app')

@section('content')

<!-- ✅ Page Title + Add Product Button -->
<h2 class="page-title mb-4">🛠️ Your Tools</h2>

<!-- ✅ Add New Product Button -->
<div class="filter-header mb-3 d-flex justify-content-end">
    <a id="openAddProductModal" href="javascript:void(0);" class="btn btn-success">
        ➕ Add New Product
    </a>
</div>

<!-- ✅ Products Table Style (لكن مع المحافظة على الكروت) -->
@if ($products->count())
<div class="products-grid">
    @foreach ($products as $product)
    <div class="product-card">
        <div class="product-image">
            @if($product->images->isNotEmpty())
                <img src="{{ asset('storage/' . $product->images->sortByDesc('created_at')->first()->image_url) }}" alt="{{ $product->name }}" class="product-img">
            @else
                <img src="{{ asset('images/default-product.png') }}" alt="No Image" class="product-img">
                <p class="no-image-text">No image uploaded for this tool yet.</p>
            @endif
        </div>

        <div class="product-details">
            <h3 class="product-name">{{ $product->name }}</h3>

            <div class="product-row d-flex justify-content-between align-items-center">
                <p class="product-price">{{ number_format($product->price, 2) }} JOD/day</p>
                <p class="product-stock">Stock: {{ $product->quantity }}</p>
            </div>

            <div class="product-actions-row d-flex justify-content-between align-items-center">
                <div class="product-actions d-flex gap-2">
                    <a href="{{ route('merchant.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                    <form method="POST" action="{{ route('merchant.products.destroy', $product->id) }}" class="delete-form" onsubmit="return confirmDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
                <span class="custom-status {{ $product->status }}">
                    {{ ucfirst($product->status) }}
                </span>
            </div>
        </div>
    </div>
    @endforeach
</div>

@else
<div class="alert alert-info mt-4">
    <h4 class="mb-2">You don’t have any tools listed yet.</h4>
    <p>Start by adding a product to get your first reservation.</p>
</div>
@endif

<!-- ✅ Add Product Modal -->
<div id="addProductModal" class="modal {{ $errors->any() ? '' : 'hidden' }}">
    <div class="modal-content" style="max-height: 80vh; overflow-y: auto;">
        <h3 class="mb-4">➕ Add New Product</h3>
        <form id="addProductForm" action="{{ route('merchant.products.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label class="form-label">Product Name:</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
                @error('name')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" required class="form-control">{{ old('description') }}</textarea>
                @error('description')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Price (JOD/day):</label>
                <input type="number" name="price" step="0.01" value="{{ old('price') }}" required class="form-control">
                @error('price')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Quantity:</label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required class="form-control">
                @error('quantity')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="form-group mb-4">
                <label class="form-label">Category:</label>
                <select name="category_id" required class="form-control">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="modal-actions d-flex justify-content-end gap-2">
                <button type="button" id="cancelAddProduct" class="btn btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-add">Add Product</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ Confirmation Modal (for Deletion) -->
<div id="confirmationModal" class="modal hidden">
    <div class="modal-content">
        <h3 class="mb-3">⚠️ Confirm Deletion</h3>
        <p>Are you sure you want to delete this product?</p>
        <div class="modal-actions d-flex justify-content-end gap-2 mt-4">
            <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
            <button id="confirmDelete" class="btn btn-danger">Delete</button>
        </div>
    </div>
</div>
<!-- ✅ Upload Image Modal -->
<div id="uploadImageModal" class="modal hidden">
    <div class="modal-content">
        <h3 class="mb-4">📤 Upload Product Images</h3>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="uploadImageForm" action="{{ route('merchant.products.uploadImage') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" id="uploadedProductId">

            <div class="form-group mb-3">
                <label class="form-label">Select Image:</label>
                <input type="file" name="image" accept="image/*" required class="form-control">
                @error('image')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <div class="modal-actions d-flex justify-content-end gap-2 mt-3">
                <input type="hidden" id="redirectTo" value="{{ session('redirectTo', 'merchant') }}">
                <button type="submit" class="btn btn-primary">Upload Image</button>
                <button type="button" id="finishUploading" class="btn btn-cancel">Finish</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ Notifications -->
@if(session('success'))
<script>
    showCustomNotification("{{ session('success') }}", "success");
</script>
@endif

@if(session('error'))
<script>
    showCustomNotification("{{ session('error') }}", "error");
</script>
@endif

<div id="customNotification" class="custom-notification hidden">
    <button id="closeCustomNotification" class="close-btn">×</button>
    <div class="icon" id="notificationIcon"></div>
    <div class="message" id="customNotificationMessage"></div>
    <div class="progress-bar" id="customProgressBar"></div>
</div>

<!-- ✅ Pagination -->
@if ($products->lastPage() > 1)
    <div class="pagination-container mt-4">
        <ul class="pagination justify-content-center">
            @for ($i = 1; $i <= $products->lastPage(); $i++)
                <li class="page-item {{ ($products->currentPage() == $i) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
        </ul>
    </div>
@endif

@endsection
