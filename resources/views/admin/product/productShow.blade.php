@extends('layouts.admin.app')

@section('content')
<!-- ======== Page Title ======== -->
<div class="product-page-header">
    <h1 class="page-title">{{ $product->name }}</h1>
</div>

<!-- ======== Product Card with Image + Details Inside ======== -->
<div class="product-card-container">
    <div class="product-details-card">

        <!-- Product Images Side -->
        <div class="product-image-side">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($product->images as $image)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $product->name }}">
                        </div>
                    @endforeach
                </div>
                @if($product->images->count() > 1)
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                @endif
            </div>
        </div>

        <!-- Product Information Side -->
        <div class="product-info-side">
            <h3>Description:</h3>
            <p>{{ $product->description }}</p>

            <h3>Price:</h3>
            <p>{{ number_format($product->price, 2) }} JOD/day</p>

            <h3>Quantity:</h3>
            <p>{{ $product->quantity }}</p>

            <h3>Category:</h3>
            <p>{{ $product->category->name }}</p>

            <h3>Status:</h3>
            <p class="product-status-{{ $product->status }}">{{ ucfirst($product->status) }}</p>

            <!-- Edit Buttons -->
            <div class="edit-product-btn-wrapper" style="margin-top: 20px; display: flex; gap: 10px;">
                <button id="openEditProductModal" class="btn btn-edit">
                    ✏️ Edit Product
                </button>

                <button id="openEditImagesModal" class="btn btn-edit">
                    📸 Edit Images
                </button>
            </div>
        </div>

    </div>
</div>

<!-- ======== Reservations Summary ======== -->
<div class="reservations-summary-custom">
    <h3>Reservations:</h3>
    <p>Total: {{ $reservationsCount }}</p>
    <p>Completed: {{ $completedReservationsCount }}</p>
    <p>Upcoming: {{ $upcomingReservationsCount }}</p>

    <a href="{{ route('admin.product.reservations', $product->id) }}" class="btn-view-reservations">View All Reservations</a>
</div>

<!-- ======== Product Reviews Section ======== -->
<div class="product-reviews-container">
    <h3>Reviews ({{ $reviews->total() }})</h3>

    @forelse ($reviews as $review)
        <div class="review-card">
            <div class="review-header">
                <img class="user-avatar" src="{{ asset('storage/' . $review->user->profile_image) }}" alt="{{ $review->user->name }}">
                <strong>{{ $review->user->name }}</strong>
                <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
            </div>
            <div class="review-body">
                <p>{{ $review->review_text }}</p>
                <span class="review-rating">Rating: {{ $review->rating }} / 5</span>
            </div>
        </div>
    @empty
        <p class="no-reviews">No reviews yet.</p>
    @endforelse

    @if ($reviews->lastPage() > 1)
        <div class="pagination-container">
            <ul class="pagination">
                @for ($i = 1; $i <= $reviews->lastPage(); $i++)
                    <li class="{{ ($reviews->currentPage() == $i) ? 'active' : '' }}">
                        <a href="{{ $reviews->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor
            </ul>
        </div>
    @endif
</div>

<!-- ======== Fixed Back Button (at Bottom Left) ======== -->
<a href="{{ route('admin.products.index') }}" class="btn-back-fixed">
    🔙 Back
</a>

<!-- ======== Edit Product Modal ======== -->
<div id="editProductModal" class="modal hidden">
    <div class="modal-content">
        <!-- Modal Header -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Edit Product</h3>
        </div>

        <!-- Modal Body -->
        <form id="editProductForm" action="{{ route('admin.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Product Name -->
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required>{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Price -->
            <div class="form-group">
                <label>Price (JOD/day):</label>
                <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" required>
            </div>

            <!-- Quantity -->
            <div class="form-group">
                <label>Quantity:</label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity', $product->quantity) }}" required>
            </div>

            <!-- Category -->
            <div class="form-group">
                <label>Category:</label>
                <select name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label>Status:</label>
                <select name="status" required>
                    <option value="available" {{ old('status', $product->status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ old('status', $product->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="unavailable" {{ old('status', $product->status) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>

            <!-- Modal Actions -->
            <div class="modal-actions">
                <button type="button" id="cancelEditProduct" class="btn btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-add">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- ======== Edit Images Modal (Fixed) ======== -->
<div id="editImagesModal" class="modal hidden">
    <div class="modal-content">
        <div style="text-align: center; margin-bottom: 15px;">
            <h3>Edit Product Images</h3>
            <p style="font-size: 14px; color: #777;">Click on an image to replace it or add new images below.</p>
        </div>

        <!-- Start Form -->
        <form id="editImagesForm" action="{{ route('admin.products.updateImages', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Existing Images Preview -->
            <div class="images-grid">
                @foreach ($product->images as $image)
                    <div class="image-wrapper">
                        <img src="{{ asset('storage/' . $image->image_url) }}" class="editable-image" alt="Product Image">
                        <input type="file" name="replace_images[{{ $image->id }}]" class="hidden-input" accept="image/*">
                    </div>
                @endforeach
            </div>

            <!-- Add New Images -->
            <div class="form-group" style="margin-top: 20px;">
                <label>Add New Images:</label>
                <input type="file" name="new_images[]" multiple accept="image/*">
            </div>

            <!-- Modal Actions -->
            <div class="modal-actions" style="margin-top: 20px;">
                <button type="button" id="cancelEditImages" class="btn btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-add">Save Changes</button>
            </div>

        </form>
        <!-- End Form -->
    </div>
</div>

<!-- ======== Image Preview Modal ======== -->
<div id="imagePreviewModal" class="modal hidden" style="position: fixed; inset: 0; background-color: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; z-index: 9999;">
    <img id="previewImage" src="" style="max-width: 90%; max-height: 90%; border-radius: 10px; box-shadow: 0 0 20px #000;">
</div>

<!-- Custom Notification Container -->
<div id="customNotification" class="notification hidden">
    <div class="notification-content">
        <span id="notificationIcon" class="notification-icon"></span>
        <span id="customNotificationMessage" class="notification-message"></span>
        <div id="customProgressBar" class="progress-bar"></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
