@extends('layouts.merchants.app')

@section('content')

<!-- ✅ Add Product Form with Unified Design -->
<div class="card card-body">
    <h2 class="form-title mb-4">➕ Add New Product</h2>

    <form action="{{ route('merchant.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row">

            <!-- Name -->
            <div class="form-col">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <!-- Price -->
            <div class="form-col">
                <label class="form-label">Price (JOD/day)</label>
                <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

        </div>

        <div class="form-row mt-4">

            <!-- Quantity -->
            <div class="form-col">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity', 1) }}" required>
                @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <!-- Category -->
            <div class="form-col">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
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

            <!-- Deliverable -->
            <div class="form-col" style="display: flex-row;">
                <label class="form-label d-block">Is Deliverable?</label>
                <input type="checkbox" name="is_deliverable" id="is_deliverable" value="1" {{ old('is_deliverable') ? 'checked' : '' }}>
                <label for="is_deliverable">Yes</label>
                @error('is_deliverable') <small class="text-danger d-block">{{ $message }}</small> @enderror
            </div>

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
            <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Rental Terms -->
        <div class="form-col mt-4">
            <label class="form-label">Rental Terms</label>
            <textarea name="usage_notes" class="form-control" rows="2">{{ old('usage_notes') }}</textarea>
            @error('usage_notes') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Form Actions -->
        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('merchant.products.index') }}" class="btn btn-secondary">← Back</a>
            <button type="submit" class="btn btn-success">Save Product</button>
        </div>

    </form>
</div>

@endsection
@push('scripts')
<script src="{{ asset('js/merchantCreatProduct.js') }}"></script>
@endpush
