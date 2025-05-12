@extends('layouts.user.app')

@section('content')

    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area bg-img bg-overlay jarallax" style="background-image: url('{{ $mainImage }}');">
        <div class="container h-100">
            <div class="row h-100 align-items-end">
                <div class="col-12">
                    <div class="breadcrumb-content d-flex align-items-center justify-content-between pb-5">
                        <h2 class="room-title">{{ $product->name }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <div class="container-fluid py-5">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-lg-2 mb-4">
                <div class="filter-sidebar p-3 shadow-sm bg-white rounded" style="position: sticky; top: 100px;">
                    <h5 class="mb-3 text-start">Filter by Category</h5>
                    <ul class="list-unstyled text-start">
                        <li><a href="{{ route('tools.all') }}" class="d-block py-2 text-dark">All Tools</a></li>
                        @foreach ($categories as $cat)
                            <li>
                                <a href="{{ route('products.by.category', $cat->id) }}" class="d-block py-2 text-dark">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Product Content -->
            <div class="col-lg-10">
                <div class="row">
                    <!-- Left Column: Product Info -->
                    <div class="col-12 col-lg-8">
                        <div class="single-room-details-area mb-50">
                            <div class="room-thumbnail-slides mb-50">
                                <div id="room-thumbnail--slide" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        @if ($product->images->isNotEmpty())
                                            @foreach ($product->images as $key => $image)
                                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/' . $image->image_url) }}"
                                                        class="d-block w-100" alt="Product Image">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="carousel-item active">
                                                <img src="{{ asset('img/logo.png') }}" class="d-block w-100"
                                                    alt="Default Image">
                                            </div>
                                        @endif
                                    </div>

                                    <ol class="carousel-indicators">
                                        @if ($product->images->isNotEmpty())
                                            @foreach ($product->images as $key => $image)
                                                <li data-target="#room-thumbnail--slide" data-slide-to="{{ $key }}"
                                                    class="{{ $key == 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/' . $image->image_url) }}"
                                                        class="d-block w-100" alt="Thumbnail">
                                                </li>
                                            @endforeach
                                        @else
                                            <li data-target="#room-thumbnail--slide" data-slide-to="0" class="active">
                                                <img src="{{ asset('img/logo.png') }}" class="d-block w-100"
                                                    alt="Default Thumbnail">
                                            </li>
                                        @endif
                                    </ol>
                                </div>
                            </div>


                            <!-- Basic Info -->
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
                                        <h6>
                                            Price/day: <span>{{ number_format($product->price, 2) }} JOD</span>
                                        </h6>
                                    </div>

                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-5">
                                <h4 class="mb-4">About This Tool</h4>
                                <p class="text-dark">{{ $product->description }}</p>

                            </div>
                            <!-- Start Chat with Seller & Report Buttons -->
                            @auth
                                @if (auth()->user()->user_type === 'user')
                                    <div class="mb-5 text-end d-flex justify-content-end gap-2">

                                        @if ($subscriptionActive)
                                            <!-- Start Chat Button -->
                                            <form method="POST" action="{{ route('chat.fromProduct', $product->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    💬 Chat with Seller
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Report Button -->
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#productReportModal">
                                            🚩 Report this Product
                                        </button>
                                    </div>
                                @endif
                            @endauth



                            <!-- Reviews -->
                            <div class="room-review-area">
                                <h4 class="mb-4">Customer Reviews</h4>


                                <div class="mb-5">
                                    <!-- Nav Tabs -->
                                    <ul class="nav nav-tabs" id="reviewTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="reviews-tab" data-bs-toggle="tab"
                                                href="#reviews-content" role="tab">
                                                Reviews ({{ $reviews->count() }})
                                            </a>
                                        </li>
                                        @auth
                                            @if (auth()->user()->user_type === 'user')
                                                <li class="nav-item">
                                                    <a class="nav-link" id="add-review-tab" data-bs-toggle="tab"
                                                        href="#add-review" role="tab">
                                                        Write Review
                                                    </a>
                                                </li>
                                            @endif
                                        @endauth
                                    </ul>

                                    <!-- Tab Content -->
                                    <div class="tab-content mt-4">
                                        <!-- Reviews List -->
                                        <div class="tab-pane fade show active" id="reviews-content" role="tabpanel">
                                            @forelse ($reviews as $review)
                                                <div class="single-room-review-area d-flex mb-4">
                                                    <div class="reviwer-thumbnail mr-4">
                                                        <img src="{{ $review->user->profile_picture ? asset('storage/' . $review->user->profile_picture) : asset('img/default-user.png') }}"
                                                            alt="User Image" width="120" height="120"
                                                            class="shadow-sm rounded" style="object-fit: cover;" />
                                                    </div>
                                                    <div class="reviwer-content w-100">
                                                        <div
                                                            class="reviwer-title-rating d-flex justify-content-between align-items-center mb-2">
                                                            <div>
                                                                <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                                            </div>
                                                            <div class="room-review-rating">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <i
                                                                        class="fa fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <p class="mb-0">{{ $review->review_text }}</p>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="alert alert-info">No reviews yet. Be the first to review this
                                                    product!</div>
                                            @endforelse

                                            {{ $reviews->links() }}
                                        </div>

                                        <!-- Add Review Form -->
                                        <div class="tab-pane fade" id="add-review" role="tabpanel">
                                            <form method="POST"
                                                action="{{ route('user.products.reviews.store', $product->id) }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Rating</label>
                                                    <div class="star-rating d-flex gap-1 mb-2">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fa fa-star star"
                                                                data-value="{{ $i }}"></i>
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
                            <!-- end of review section -->
                        </div> <!-- end of product details -->
                    </div> <!-- end of left column -->

                    <!-- Reservation Sidebar -->
                    <div class="col-12 col-lg-4" style="margin-left: auto; margin-right: 0;">
                        <div class="hotel-reservation--area mb-100 sticky-inside">
                            <div class="bg-white p-4 shadow-sm rounded">
                                <h4 class="mb-4">Rental Details</h4>

                                <form id="rentalForm" action="{{ route('user.rentals.store') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" id="daily_price" value="{{ $product->price }}">

                                    <!-- Rental Dates -->
                                    <div class="form-group mb-3">
                                        <label for="start_date">Start Date</label>
                                        <input type="text" id="start_date" name="start_date" class="form-control"
                                            placeholder="e.g. 2025-04-24" data-product-id="{{ $product->id }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="end_date">End Date</label>
                                        <input type="text" id="end_date" name="end_date" class="form-control"
                                            placeholder="e.g. 2025-04-27">
                                    </div>

                                    <!-- Price Preview -->
                                    <div id="price-message" class="mt-2 text-primary fw-bold"></div>

                                    <!-- Optional Comment -->
                                    <div class="form-group mt-3">
                                        <label for="note">Comment (Optional)</label>
                                        <textarea name="note" id="note" class="form-control" rows="3"
                                            placeholder="Any special instructions or notes?"></textarea>
                                    </div>



                                    <!-- ✅ START: Delivery Option + Location -->
                                    <div class="form-group form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="deliveryCheckbox"
                                            name="wants_delivery">
                                        <label class="form-check-label" for="deliveryCheckbox">
                                            I want delivery
                                        </label>
                                    </div>

                                    <div class="form-group mt-3 d-none" id="locationInputWrapper">
                                        <label for="deliveryLocation">Your Location (within Amman)</label>
                                        <input type="text" class="form-control" name="delivery_location"
                                            id="deliveryLocation" placeholder="e.g. Abdoun, Amman or Tla' Al-Ali, Amman">
                                        <small class="text-danger d-none" id="locationError">
                                            Location is required and must be within Amman.
                                        </small>
                                    </div>

                                    <!-- ✅ END -->

                                    <!-- Terms & Conditions Checkbox -->
                                    <div class="form-group form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                        <label class="form-check-label" for="termsCheck">
                                            I agree to the terms and conditions
                                        </label>
                                    </div>

                                    <!-- Reserve Button -->
                                    <button id="reserveBtn" type="button" class="btn btn-primary w-100 mt-4"
                                        @guest disabled @endguest
                                        @auth
@if (auth()->user()->user_type !== 'user') disabled @endif @endauth>
                                        Reserve Now
                                    </button>

                                    <!-- Message if button is disabled -->
                                    @guest
                                        <p class="text-center text-muted mt-2">Only registered users can make reservations.</p>
                                    @else
                                        @if (auth()->user()->user_type !== 'user')
                                            <p class="text-center text-muted mt-2">Only users can make reservations.</p>
                                        @endif
                                    @endguest

                                </form>

                                <!-- Read Terms Link -->
                                <a href="javascript:void(0);" id="readTermsLink" class="text-primary d-inline-block mt-2"
                                    style="text-decoration: underline;">
                                    Read Terms
                                </a>
                            </div>
                        </div>
                    </div> <!-- end sidebar -->

                </div> <!-- row -->
            </div> <!-- col-lg-10 -->
        </div> <!-- row -->
    </div> <!-- container -->


    <!-- 💳 Modal: Payment Info -->
    <div class="modal fade" id="cardModal" tabindex="-1" aria-labelledby="cardModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="cardForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cardModalLabel">Enter Credit Card Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Card Name -->
                    <div class="form-group mb-3">
                        <label for="cardName" class="form-label">Name on Card</label>
                        <input type="text" id="cardName" class="form-control" placeholder="John Doe">
                    </div>

                    <!-- Card Number -->
                    <div class="form-group mb-3">
                        <label for="cardNumber" class="form-label">Card Number</label>
                        <input type="text" id="cardNumber" class="form-control" placeholder="1234 5678 9012 3456">
                    </div>

                    <!-- Expiration -->
                    <div class="form-group mb-3">
                        <label for="expiry" class="form-label">Expiration Date (MM/YY)</label>
                        <input type="text" id="expiry" class="form-control" placeholder="MM/YY">
                    </div>

                    <!-- CVV -->
                    <div class="form-group mb-3">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" id="cvv" class="form-control" placeholder="3 digits">
                    </div>

                    <!-- Payment Option -->
                    <div class="form-group mb-2">
                        <label for="popupPaymentOption" class="form-label">Payment Option</label>
                        <select id="popupPaymentOption" class="form-select">
                            <option value="">-- Choose --</option>
                            <option value="10">Pay 10% Now</option>
                            <option value="100">Pay Full Amount</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success w-100">💳 Pay & Reserve</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Terms & Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Booking Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <ul class="list-unstyled">

                        <li>1. The renter must respect the rental policy. Any breach of these terms may lead to legal
                            accountability.</li>
                        <li>2. Please inspect the tool carefully before pickup. Report any issues immediately.</li>
                        <li>3. If your account is deleted due to misuse, all future reservations will be cancelled without
                            refund.</li>
                        <li>4. The renter assumes full responsibility for the tool from the moment of pickup.</li>
                        <li>5. The agreed payment must be made upon receiving the tool.</li>

                        @if ($product->usage_notes)
                            @php
                                $extraNotes = preg_split("/[\r\n]+/", $product->usage_notes);
                            @endphp
                            @foreach ($extraNotes as $index => $note)
                                <li>{{ 8 + $index }}. {{ $note }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>










    {{--  Product Report Modal --}}


    <div class="modal fade" id="productReportModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('reservations.report', $product->id) }}">
                @csrf

                <!-- hidden fields -->
                <input type="hidden" name="type" value="product">
                <input type="hidden" name="target_id" value="{{ $product->id }}">

                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">📝 Report Product - {{ $product->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2 text-muted">
                            You are reporting an issue with the product:
                            <strong>{{ $product->name }}</strong>
                        </p>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject (Optional)</label>
                            <input type="text" name="subject" class="form-control"
                                placeholder="e.g. Misleading pictures">
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Describe the issue</label>
                            <textarea name="message" class="form-control" required placeholder="Please explain the problem..." rows="4"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Submit Report</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating .fa-star');
            const ratingInput = document.getElementById('rating');

            if (stars.length && ratingInput) {
                stars.forEach(star => star.classList.add('text-muted'));

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
                    resetStars();
                }

                if (ratingInput.value) {
                    setRating(parseInt(ratingInput.value));
                }
            }

            const reviewForm = document.querySelector('#add-review form');
            const reviewText = reviewForm?.querySelector('textarea[name="review_text"]');

            reviewForm?.addEventListener('submit', function(e) {
                if (!ratingInput.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Rating',
                        text: 'Please select a star rating before submitting.'
                    });
                }

                if (!reviewText.value.trim()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Review',
                        text: 'Please write your review before submitting.'
                    });
                }
            });
        });
    </script>

    @if (session('reservation_error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Reservation Failed',
                text: @json(session('reservation_error'))
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
                text: @json($errors->first())
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Done',
                text: @json(session('success'))
            });
            document.addEventListener('DOMContentLoaded', function() {
                const goToReviewBtn = document.getElementById('goToReviewTab');
                const addReviewTab = document.getElementById('add-review-tab');

                if (goToReviewBtn && addReviewTab) {
                    goToReviewBtn.addEventListener('click', function() {
                        const tab = new bootstrap.Tab(addReviewTab);
                        tab.show();
                        // scroll to form if needed
                        document.getElementById('add-review').scrollIntoView({
                            behavior: 'smooth'
                        });
                    });
                }
            });
        </script>
    @endif

    <script src="{{ asset('js/reservation.js') }}"></script>
    <script>
        const productId = @json($product->id);
    </script>
@endpush
