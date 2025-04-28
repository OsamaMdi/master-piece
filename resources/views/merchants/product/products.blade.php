@extends('layouts.merchants.app')

@section('content')

<!-- ✅ Page Title -->
<h2 class="page-title mb-4">🛠️ Your Tools</h2>

<!-- ✅ Add New Product Button -->
<div class="filter-header">
    <a id="openAddProductModal" href="javascript:void(0);" class="btn btn-success force-right">
        ➕ Add New Product
    </a>
</div>

<!-- ✅ Table for Products -->
@if($products->count())
    <table class="table table-bordered mt-4" style="margin-top: 6%;">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
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
                        <img src="{{ asset('images/default-product.png') }}" alt="No Image" class="product-img">
                    @endif
                </td>
                <td>{{ $product->name }}</td>
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
                <td class="text-center" style="display: flex; gap: 8px; justify-content: center;">
                    <a href="{{ route('merchant.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                    <form method="POST" action="{{ route('merchant.products.destroy', $product->id) }}" class="delete-form" >
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" style="width: 38px; height: 33px; ">
                            <i class="fas fa-trash-alt" ></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-info mt-4" style="margin-top: 6%;">
        No products found. Start by adding your first tool!
    </div>
@endif
<!-- ✅ Add Product Modal -->
<div id="addProductModal" class="modal {{ $errors->any() ? 'active' : 'hidden' }}">
    <div class="modal-content" style="max-height: 80vh; overflow-y: auto;">
        <h3 class="mb-4">➕ Add New Product</h3>

        <form id="addProductForm" action="{{ route('merchant.products.store') }}" method="POST">
            @csrf

            <!-- Product Name Input -->
            <div class="form-group mb-3">
                <label class="form-label">Product Name:</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
                @error('name')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Description Textarea -->
            <div class="form-group mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" required class="form-control">{{ old('description') }}</textarea>
                @error('description')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Price Input -->
            <div class="form-group mb-3">
                <label class="form-label">Price (JOD/day):</label>
                <input type="number" name="price" step="0.01" value="{{ old('price') }}" required class="form-control">
                @error('price')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Quantity Input -->
            <div class="form-group mb-3">
                <label class="form-label">Quantity:</label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required class="form-control">
                @error('quantity')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Category Select -->
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

            <!-- Deliverable Checkbox -->
            <div class="form-group mb-3">
                <div style="display: flex;  flex-start; gap: 8px;">
                    <input type="checkbox" name="is_deliverable" id="is_deliverable" value="1" style="margin: 0; width: 20px; height: 20px;">
                    <label for="is_deliverable" style="margin: 0;">Deliverable?</label>
                </div>
                @error('is_deliverable')<small class="error-text">{{ $message }}</small>@enderror
            </div>


            <!-- Usage Notes Textarea -->
            <div class="form-group mb-3">
                <label class="form-label">Usage Notes (optional):</label>
                <textarea name="usage_notes" class="form-control">{{ old('usage_notes') }}</textarea>
                @error('usage_notes')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Modal Actions (Buttons) -->
            <div class="modal-actions d-flex justify-content-end gap-2">
                <button type="button" id="cancelAddProduct" class="btn btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-add">Add Product</button>
            </div>

        </form>
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

            <!-- Image Upload Field -->
            <div class="form-group mb-3">
                <label class="form-label">Select Image:</label>
                <input type="file" name="image" accept="image/*" required class="form-control">
                @error('image')<small class="error-text">{{ $message }}</small>@enderror
            </div>

            <!-- Modal Actions -->
            <div class="modal-actions d-flex justify-content-end gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Upload Image</button>
                <button type="button" id="finishUploading" class="btn btn-cancel">Finish</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ Notification Popups -->
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

<!-- ✅ Custom Notification Component -->
<div id="customNotification" class="custom-notification hidden">
    <button id="closeCustomNotification" class="close-btn">×</button>
    <div class="icon" id="notificationIcon"></div>
    <div class="message" id="customNotificationMessage"></div>
    <div class="progress-bar" id="customProgressBar"></div>
</div>

<!-- ✅ Pagination Section -->
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


<!-- ======== Confirmation Modal (for Deletion) ======== -->
<div id="confirmationModal" class="modal hidden">
    <div class="modal-content">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this product?</p>
        <div class="modal-actions d-flex justify-content-end gap-2 mt-3">
            <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
            <button id="confirmDelete" class="btn btn-danger">Delete</button>
        </div>
    </div>
</div>




@endsection
