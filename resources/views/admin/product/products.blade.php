@extends('layouts.merchants.app')

@section('content')

<!-- ======== Page Header and Add Product Button ======== -->
<div class="page-header">
    <h2 class="page-title">🛠️ Your Tools</h2>
    <a id="openAddProductModal" href="javascript:void(0);" class="btn btn-add">
        + Add New Product
    </a>
</div>

<!-- ======== Products Grid ======== -->
<div class="products-grid">
    @forelse ($products as $product)
    <div class="product-card">
        <div class="product-image">
            @if($product->images->isNotEmpty())
                <img src="{{ asset('storage/' . $product->images->sortByDesc('created_at')->first()->image_url) }}" alt="{{ $product->name }}">
            @else
                <img src="{{ asset('images/default-product.png') }}" alt="No Image Available">
                <p class="no-image-text">No image uploaded for this tool yet.</p>
            @endif
        </div>

        <div class="product-details">
            {{-- اسم المنتج فقط --}}
            <h3 class="product-name">{{ $product->name }}</h3>

            {{-- السعر والستوك --}}
            <div class="product-row">
                <p class="product-price">{{ number_format($product->price, 2) }} JOD/day</p>
                <p class="product-stock">Stock: {{ $product->quantity }}</p>
            </div>

            {{-- الأكشن + الحالة --}}
            <div class="product-actions-row">
                <div class="product-actions">
                    <a href="{{ route('merchant.products.show', $product->id) }}" class="btn btn-view">👁️ View</a>
                    <form method="POST" action="{{ route('merchant.products.destroy', $product->id) }}" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-delete delete-btn">🗑️ Delete</button>
                    </form>
                </div>
                <p class="product-status {{ $product->status }}">{{ ucfirst($product->status) }}</p>
            </div>
        </div>

    </div>
    @empty
    <div class="empty-state">
        <h2> You don’t have any tools listed yet.</h2>
        <p>Start by adding a product to get your first reservation.</p>
    </div>
    @endforelse
</div>

<!-- ======== Confirmation Modal (for Deletion) ======== -->
<div id="confirmationModal" class="modal hidden">
    <div class="modal-content">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this product?</p>
        <div class="modal-actions">
            <button id="cancelDelete" class="btn">Cancel</button>
            <button id="confirmDelete" class="btn">Delete</button>
        </div>
    </div>
</div>

<!-- ======== Add Product Modal ======== -->
<div id="addProductModal" class="modal {{ $errors->any() ? '' : 'hidden' }}">
    <div class="modal-content">
        <h3>Add New Product</h3>
        <form id="addProductForm" action="{{ route('merchant.products.store') }}" method="POST">
            @csrf

            <!-- Product Name Field -->
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                @error('name')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Description Field -->
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required>{{ old('description') }}</textarea>
                @error('description')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Price Field -->
            <div class="form-group">
                <label>Price (JOD/day):</label>
                <input type="number" name="price" step="0.01" value="{{ old('price') }}" required>
                @error('price')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Quantity Field -->
            <div class="form-group">
                <label>Quantity:</label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
                @error('quantity')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Category Field -->
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

            <!-- Modal Actions -->
            <div class="modal-actions">
                <button type="button" id="cancelAddProduct" class="btn btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-add">Add Product</button>
            </div>
        </form>
    </div>
</div>

<!-- ======== Upload Image Modal ======== -->
<div id="uploadImageModal" class="modal hidden">
    <div class="modal-content">
        <h3>Upload Product Images</h3>

        @if ($errors->any())
        <div class="error-block">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="uploadImageForm" action="{{ route('merchant.products.uploadImage') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" id="uploadedProductId">

            <!-- Image Field -->
            <div class="form-group">
                <label>Select Image:</label>
                <input type="file" name="image" accept="image/*" required>
                @error('image')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Modal Actions -->
            <div class="modal-actions">
                <button type="submit" class="btn btn-primary">Upload Image</button>
                <button type="button" id="finishUploading" class="btn btn-cancel">Finish</button>
            </div>
        </form>
    </div>
</div>

<!-- ======== Notification Popups for Success/Error ======== -->
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

<!-- Default Notification Popup -->
<div id="notificationPopup" class="notification hidden">
    <div id="notificationContent">
        <p id="notificationMessage"></p>
        <div id="notificationActions" class="hidden">
            <button id="tryAgainBtn" class="btn btn-primary">Try Again</button>
            <button id="cancelBtn" class="btn btn-cancel">Cancel</button>
        </div>
    </div>
</div>

<!-- Custom Notification with Progress Bar -->
<div id="customNotification" class="custom-notification hidden">
    <button id="closeCustomNotification" class="close-btn">×</button>
    <div class="icon" id="notificationIcon"></div>
    <div class="message" id="customNotificationMessage"></div>
    <div class="progress-bar" id="customProgressBar"></div>
</div>

<!-- ======== Pagination ======== -->
@if ($products->lastPage() > 1)
    <div class="pagination-container">
        <ul class="pagination">
            @for ($i = 1; $i <= $products->lastPage(); $i++)
                <li class="{{ ($products->currentPage() == $i) ? 'active' : '' }}">
                    <a href="{{ $products->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
        </ul>
    </div>
@endif

@endsection
