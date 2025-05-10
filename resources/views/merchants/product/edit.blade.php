@extends('layouts.merchants.app')

@section('content')
<div class="card card-body">
    <h2 class="form-title mb-4">✏️ Edit Product</h2>

    <form method="POST" action="{{ route('merchant.products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-col">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-col">
                <label class="form-label">Price (JOD/day)</label>
                <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $product->price) }}" required>
                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-row mt-4">
            <div class="form-col">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" min="1" class="form-control" value="{{ old('quantity', $product->quantity) }}" required>
                @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-col">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-row mt-4">
            <div class="form-col">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required>{{ old('description', $product->description) }}</textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-col">
                <label class="form-label">Rental Terms</label>
                <textarea name="usage_notes" class="form-control" rows="2">{{ old('usage_notes', $product->usage_notes) }}</textarea>
                @error('usage_notes') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-col mt-4">
            <label class="form-label d-block">Is Deliverable?</label>
            <input type="checkbox" name="is_deliverable" id="is_deliverable" value="1" {{ old('is_deliverable', $product->is_deliverable) ? 'checked' : '' }}>
            <label for="is_deliverable">Yes</label>
            @error('is_deliverable') <small class="text-danger d-block">{{ $message }}</small> @enderror
        </div>

        <!-- ✅ Action Buttons -->
        <div class="mt-4 text-end" style="margin-top: 2rem;">
            <a href="{{ route('merchant.products.index') }}" class="btn btn-secondary">← Cancel</a>
            <button type="submit" class="btn btn-success">Update Product</button>
            <button type="button"
                    class="btn btn-outline-dark ms-auto d-block"
                    onclick="document.getElementById('editImagesModal').classList.remove('hidden')">
                Edit Images
            </button>
        </div>
    </form>
</div>

<!-- ✅ Edit Images Modal -->
<div id="editImagesModal" class="modal hidden">
    <div class="modal-content" style="max-width: 900px; width: 95%; max-height: 90vh; overflow-y: auto;">
        <h3>Edit Product Images</h3>
        <p style="font-size: 14px; color: #777;">Click on an image to replace it or add new images below.</p>

        <form id="editImagesForm" action="{{ route('merchant.products.updateImages', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Existing Images -->
            <div class="images-grid d-flex flex-wrap gap-3 mt-3">
                @foreach ($product->images as $image)
                    <div class="image-wrapper position-relative" data-image-id="{{ $image->id }}">
                        <img src="{{ asset('storage/' . $image->image_url) }}" class="editable-image" style="width: 120px; height: 120px; object-fit: cover;">
                        <input type="file" name="replace_images[{{ $image->id }}]" class="hidden-input d-none" accept="image/*">
                        <button type="button"
                                class="btn btn-sm btn-danger delete-image-btn"
                                data-image-id="{{ $image->id }}"
                                style="position: absolute; top: 5px; right: 5px; padding: 2px 6px; font-weight: bold;">
                            ×
                        </button>
                    </div>
                @endforeach
            </div>

            <!-- Deleted Image IDs Container -->
            <div id="deletedImagesContainer"></div>

            <!-- Upload New Images -->
            <div class="form-group mt-4">
                <label>Add New Images:</label>
                <input type="file" name="new_images[]" class="form-control" multiple accept="image/*">
            </div>

            <!-- Modal Actions -->
            <div class="modal-actions d-flex justify-content-end gap-2 mt-3">
                <button type="button" onclick="document.getElementById('editImagesModal').classList.add('hidden')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/merchantEditProduct.js') }}"></script>
@endpush
