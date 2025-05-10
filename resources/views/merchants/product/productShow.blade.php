@extends('layouts.merchants.app')

@section('content')
<!-- ======== Page Title ======== -->
<div class="product-page-header">
    <h1 class="page-title">{{ $product->name }}</h1>
</div>

<!-- ======== Product Card with Image + Details Inside ======== -->
<div class="product-card-container">
    <div class="product-details-card">

      <!-- Product Images Side -->
<div class="product-image-side" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
    <div>
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
</div>

<!-- Product Information Side -->
<div class="product-info-side">
    <h3>Description:</h3>
    <p>{{ $product->description }}</p>

    <div class="info-row">
        <h3>Price:</h3>
        <p>{{ number_format($product->price, 2) }} JOD/day</p>
    </div>

    <div class="info-row">
        <h3>Quantity:</h3>
        <p>{{ $product->quantity }}</p>
    </div>

    <div class="info-row">
        <h3>Category:</h3>
        <p>{{ $product->category->name }}</p>
    </div>

    @php
        $statusClass = match($product->status) {
            'available' => 'custom-status available',
            'blocked' => 'custom-status blocked',
            'maintenance' => 'custom-status maintenance',
            default => 'custom-status unknown'
        };
    @endphp

    <div class="info-row">
        <h3>Status:</h3>
        <p class="{{ $statusClass }}">{{ ucfirst($product->status ?? 'Unknown') }}</p>
    </div>

    <div class="info-row">
        <h3>Deliverable:</h3>
        <p>{{ $product->is_deliverable ? 'Yes' : 'No' }}</p>
    </div>

    <h3>Usage Notes:</h3>
    <p>{{ $product->usage_notes }}</p>

    <div class="info-row">
        <h3>Created At:</h3>
        <p>{{ $product->created_at->format('Y-m-d H:i') }}</p>
    </div>

    <div class="info-row">
        <h3>Last Updated:</h3>
        <p>{{ $product->updated_at->format('Y-m-d H:i') }}</p>
    </div>



    <div class="edit-product-btn-wrapper" style="margin-top: 20px; display: flex; gap: 10px;">
        <a href="javascript:history.back()" class="btn btn-secondary">← Back</a>

        @if($product->status === 'available')
            <button type="button" id="openDisableProductModal" class="btn btn-warning">
                ❌ Disable Product
            </button>
        @elseif($product->status === 'maintenance')
            <form action="{{ route('merchant.products.toggleStatus', $product->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-edit">
                    ✅ Enable Product
                </button>
            </form>
        @endif
    </div>
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

    <a href="{{ route('merchant.product.reservations', $product->id) }}" class="btn-view-reservations">View All Reservations</a>
</div>

<!-- ======== Product Reviews Section ======== -->
<div class="product-reviews-container">
    <h3>Reviews ({{ $reviews->total() }})</h3>

    @foreach ($reviews as $review)
        @php
            $report = \App\Models\Report::where('reportable_type', \App\Models\Review::class)
                ->where('reportable_id', $review->id)
                ->where('status', 'pending')
                ->first();
        @endphp

        <div class="review-card">
            <div class="review-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img class="user-avatar"
                        src="{{ $review->user->profile_picture ? asset('storage/' . $review->user->profile_picture) : asset('img/default-user.png') }}"
                        alt="{{ $review->user->name }}">
                    <strong class="ms-2">{{ $review->user->name }}</strong>
                    <span class="review-date ms-2">{{ $review->created_at->format('M d, Y') }}</span>

                    @if ($report)
                        {{-- Badge if reported --}}
                        <span class="badge badge-danger ms-2">🚩 Reported</span>
                    @endif
                </div>

                <div>
                    @if (!$report)
                        {{-- 🚩 زر التبليغ --}}
                        <button type="button"
                            class="btn btn-sm btn-warning openReportModalBtn"
                            data-reportable-type="{{ get_class($review) }}"
                            data-reportable-id="{{ $review->id }}"
                            data-target-type="review"
                            data-report-url="{{ route('reports.send') }}">
                            🚩 Report
                        </button>
                    @else
                        {{-- ✔️ زر رفع البلاغ --}}
                        <form action="{{ route('reports.resolve', $report->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">
                                ✔️ Resolve Report
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="review-body">
                <p>{{ $review->review_text }}</p>
                <span class="review-rating">Rating: {{ $review->rating }} / 5</span>
            </div>
        </div>
    @endforeach

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

{{-- <!-- Custom Notification Container -->
<div id="customNotification" class="notification hidden">
    <div class="notification-content">
        <span id="notificationIcon" class="notification-icon"></span>
        <span id="customNotificationMessage" class="notification-message"></span>
        <div id="customProgressBar" class="progress-bar"></div>
    </div>
</div> --}}
<script src="{{ asset('js/poppDisableProduct.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const disableProductUrl = "{{ route('merchant.products.disable', $product->id) }}";
   const csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/poppReport.js') }}"></script>
@endsection
