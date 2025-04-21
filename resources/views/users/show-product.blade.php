@extends('layouts.user.app')

@section('content')

@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
        });
    </script>
@endif

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area bg-img bg-overlay jarallax" style="background-image: url('{{ $mainImage }}');">
    <div class="container h-100">
        <div class="row h-100 align-items-end">
            <div class="col-12">
                <div class="breadcrumb-content d-flex align-items-center justify-content-between pb-5">
                    <h2 class="room-title">{{ $product->name }}</h2>
                    <h2 class="room-price">{{ number_format($product->price, 2) }} JOD <span>/ Day</span></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->

<!-- Rooms Area Start -->
<div class="roberto-rooms-area section-padding-100-0">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <!-- Single Room Details Area -->
                <div class="single-room-details-area mb-50">

                    <!-- Room Thumbnail Slides -->
                    @if ($product->images->count())
                    <div class="room-thumbnail-slides mb-50">
                        <div id="room-thumbnail--slide" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($product->images as $key => $image)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" class="d-block w-100" alt="Product Image">
                                </div>
                                @endforeach
                            </div>
                            <ol class="carousel-indicators">
                                @foreach ($product->images as $key => $image)
                                <li data-target="#room-thumbnail--slide" data-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_url) }}" class="d-block w-100" alt="Thumbnail">
                                </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                    @endif

                    <!-- Room Features -->
                    <div class="room-features-area mb-50">
                        <div class="row">
                            <div class="col-6 col-md-3">
                                <h6>Category: <span>{{ $product->category->name }}</span></h6>
                            </div>
                            <div class="col-6 col-md-3">
                                <h6>Merchant: <span>{{ $product->user->name }}</span></h6>
                            </div>
                            <div class="col-6 col-md-3">
                                <h6>Stock: <span>{{ $product->quantity }}</span></h6>
                            </div>
                            <div class="col-6 col-md-3">
                                <h6>Condition: <span class="text-capitalize">{{ $product->condition }}</span></h6>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-5">
                        <h4 class="mb-4">About This Tool</h4>
                        <p class="text-dark">{{ $product->description }}</p>
                    </div>

                    <!-- Reviews Section -->
                    <div class="room-review-area">
                        <h4 class="mb-4">Customer Reviews</h4>

                        <!-- Review Tabs -->
                        <div class="mb-5">
                            <ul class="nav nav-tabs" id="reviewTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="reviews-tab" data-toggle="tab" href="#reviews-content">Reviews ({{ $reviews->count() }})</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="add-review-tab" data-toggle="tab" href="#add-review">Write Review</a>
                                </li>
                            </ul>

                            <div class="tab-content mt-4">
                                <!-- Reviews List -->
                                <div class="tab-pane fade show active" id="reviews-content">
                                    @forelse ($reviews as $review)
                                    <div class="single-room-review-area d-flex mb-4">
                                        <div class="reviwer-thumbnail mr-4">
                                            <img src="{{ asset('images/default-avatar.png') }}" alt="" width="70">
                                        </div>
                                        <div class="reviwer-content w-100">
                                            <div class="reviwer-title-rating d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                    <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                                </div>
                                                <div class="room-review-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fa fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="mb-0">{{ $review->review_text }}</p>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="alert alert-info">No reviews yet. Be the first to review this product!</div>
                                    @endforelse

                                    {{ $reviews->links() }}
                                </div>

                                <!-- Add Review Form -->
                                <div class="tab-pane fade" id="add-review">
                                    <form method="POST" action="{{ route('user.products.reviews.store', $product->id) }}">
                                        @csrf

                                        <div class="form-group">
                                            <label>Rating</label>
                                            <div class="star-rating d-flex gap-1 mb-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fa fa-star star" data-value="{{ $i }}"></i>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="rating" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Your Review</label>
                                            <textarea name="review_text" class="form-control" rows="5" required></textarea>
                                        </div>

                                        <button type="submit" class="btn roberto-btn">Submit Review</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservation Sidebar -->
            <div class="col-12 col-lg-4">
                <div class="hotel-reservation--area mb-100 sticky-inside">

                    <div class="bg-white p-4 shadow-sm rounded">
                        <h4 class="mb-4">Rental Details</h4>

                        <form action="{{-- {{ route('user.rentals.store') }} --}}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="form-group">
                                <label>Rental Period</label>
                                <div class="input-daterange">
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="date" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="col-6">
                                            <input type="date" name="end_date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="quantity" class="form-control"
                                       min="1" max="{{ $product->quantity }}" value="1" required>
                            </div>

                            <div class="form-group">
                                <label>Total Price</label>
                                <div class="h5 text-dark" id="pricePreview">
                                    {{ number_format($product->price, 2) }} JOD
                                </div>
                            </div>

                            <button type="submit" class="btn roberto-btn btn-block">Reserve Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Rooms Area End -->

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // === ⭐ Star Rating Logic ===
    const stars = document.querySelectorAll('.star-rating .fa-star');
    const ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        star.classList.add('text-muted');
    });

    stars.forEach((star, index) => {
        star.addEventListener('mouseover', () => highlightStars(index));
        star.addEventListener('mouseout', () => resetStars());
        star.addEventListener('click', () => setRating(index + 1));
    });

    function highlightStars(index) {
        stars.forEach((s, i) => {
            s.classList.toggle('text-warning', i <= index);
            s.classList.toggle('text-muted', i > index);
        });
    }

    function resetStars() {
        const currentRating = parseInt(ratingInput.value) || 0;
        stars.forEach((s, i) => {
            s.classList.toggle('text-warning', i < currentRating);
            s.classList.toggle('text-muted', i >= currentRating);
        });
    }

    function setRating(rating) {
        ratingInput.value = rating;
        resetStars(); // هذا ضروري لتطبيق اللون الصحيح
    }

    if (ratingInput.value) {
        setRating(parseInt(ratingInput.value));
    }

    // === 🛡️ Validate Review Before Submit ===
    const reviewForm = document.querySelector('#add-review form');
    const reviewText = reviewForm.querySelector('textarea[name="review_text"]');

    reviewForm.addEventListener('submit', function (e) {
        if (!ratingInput.value) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Missing Rating',
                text: 'Please select a star rating before submitting your review.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        if (!reviewText.value.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Missing Review',
                text: 'Please write your review before submitting.',
                confirmButtonColor: '#3085d6',
            });
        }
    });

    // === 💰 Price Calculation ===
    const dailyPrice = {{ $product->price }};
    const startDate = document.querySelector('input[name="start_date"]');
    const endDate = document.querySelector('input[name="end_date"]');
    const quantity = document.querySelector('input[name="quantity"]');
    const pricePreview = document.getElementById('pricePreview');

    function calculatePrice() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diffTime = Math.max(end - start, 0);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;
            const total = diffDays * dailyPrice * quantity.value;
            pricePreview.textContent = total.toFixed(2) + ' JOD';
        }
    }

    [startDate, endDate, quantity].forEach(el => {
        el.addEventListener('change', calculatePrice);
    });
});
</script>
@endpush
