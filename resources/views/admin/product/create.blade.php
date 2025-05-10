@extends('layouts.admin.app')

@section('content')

<!-- ✅ Admin Add Product Page -->
<div class="card card-body">
    <h2 class="form-title mb-4">➕ Add New Product</h2>

    <form id="addProductForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row">

            <!-- Product Name -->
            <div class="form-col">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <!-- Price -->
            <div class="form-col">
                <label class="form-label">Price (JOD/day)</label>
                <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}">
                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

        </div>

        <div class="form-row mt-4">

            <!-- Quantity -->
            <div class="form-col">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity', 1) }}">
                @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <!-- Category -->
            <div class="form-col">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

        </div>

        <div class="form-row mt-4">

            <!-- Merchant -->
            <div class="form-col">
                <label class="form-label">Merchant</label>
                <select name="merchant_id" class="form-select">
                    <option value="">Select Merchant</option>
                    @foreach($merchants as $merchant)
                        <option value="{{ $merchant->id }}" {{ old('merchant_id') == $merchant->id ? 'selected' : '' }}>
                            {{ $merchant->name }} ({{ $merchant->email }})
                        </option>
                    @endforeach
                </select>
                @error('merchant_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

        </div>

        <div class="form-row mt-4">

            <!-- Image Upload -->
            <div class="form-col">
                <label class="form-label">Product Images</label>
                <input type="file" id="multiImageInput" name="images[]" class="form-control" accept="image/*" multiple>
                <div id="imagePreviewContainer" class="d-flex mt-2"></div>
                @error('images') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

        </div>

        <!-- Description -->
        <div class="form-col mt-4">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Actions -->
        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← Back</a>
            <button type="submit" class="btn btn-primary">Save Product</button>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/adminCreateProduct.js') }}"></script>
@endpush
