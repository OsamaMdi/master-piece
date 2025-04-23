@extends('layouts.user.app')
@section('content')

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area bg-img bg-overlay jarallax" style="background-image: url({{ asset('img/tool3.png') }});">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <div class="breadcrumb-content text-center">
                    <h2 class="page-title">About Rentify</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->

<!-- About Us Area Start -->
<section class="roberto-about-us-area section-padding-100-0">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-6">
                <div class="about-thumbnail pr-lg-5 mb-100 wow fadeInUp" data-wow-delay="100ms">
                    <img src="{{ asset('img/tool1.png') }}" alt="About Rentify">
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="section-heading wow fadeInUp" data-wow-delay="300ms">
                    <h6>About Us</h6>
                    <h2>Smart Solutions for Specialized Tool Rentals</h2>
                </div>
                <div class="about-content mb-100 wow fadeInUp" data-wow-delay="500ms">
                    <p><strong>Rentify</strong> offers an innovative platform that provides access to high-quality, mid-sized tools that aren't needed daily, but are essential when required—at a fraction of the cost of ownership.</p>
                    <p>Whether you're working in agriculture, photography, professional cleaning, or need special equipment for a project, we provide reliable, well-maintained tools ready for use.</p>
                    <p>Our goal is to be the go-to platform for anyone looking for smart, affordable, and convenient tool rentals.</p>
                    <img src="{{ asset('img/core-img/signature.png') }}" alt="Signature">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About Us Area End -->

<!-- Video Area Start -->
<div class="roberto--video--area bg-img bg-overlay jarallax section-padding-0-100" style="background-image: url({{ asset('img/tool2.png') }});">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center">
            <div class="col-12 col-md-8">
                <div class="section-heading text-center white wow fadeInUp" data-wow-delay="100ms">
                    <h6>Discover More</h6>
                    <h2>How Rentify Works</h2>
                </div>
                <div class="video-content-area mt-100 wow fadeInUp" data-wow-delay="500ms">
                    <a href="https://www.youtube.com/watch?v=LcPW1ebRfNY" class="video-play-btn" target="_blank">
                        <i class="fa fa-play"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Video Area End -->



<!-- Service Area Start -->
<section class="roberto-service-area section-padding-100-0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading text-center wow fadeInUp" data-wow-delay="100ms">
                    <h6>What We Offer</h6>
                    <h2>Tool Categories</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="single-service-area mb-100 wow fadeInUp" data-wow-delay="300ms">
                    <img src="{{ asset('img/agriculture-tools.png') }}" alt="Agricultural Tools">
                    <div class="service-title d-flex align-items-center justify-content-center">
                        <h5>Agricultural Tools</h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="single-service-area mb-100 wow fadeInUp" data-wow-delay="500ms">
                    <img src="{{ asset('img/photography-gear.png') }}" alt="Photography Equipment">
                    <div class="service-title d-flex align-items-center justify-content-center">
                        <h5>Photography Equipment</h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="single-service-area mb-100 wow fadeInUp" data-wow-delay="700ms">
                    <img src="{{ asset('img/cleaning-tools.png') }}" alt="Professional Cleaning Tools">
                    <div class="service-title d-flex align-items-center justify-content-center">
                        <h5>Professional Cleaning Tools</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Service Area End -->

<div class="row">
    <div class="col-12">
        <div class="section-heading text-center wow fadeInUp" data-wow-delay="100ms">
            <h2>What Our Users Say</h2>
        </div>
    </div>

    @forelse ($recentReviews as $review)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="bg-white rounded shadow-sm h-100 p-4 d-flex flex-column justify-content-between">
                <!-- User Info -->
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $review->user->profile_picture ? asset('storage/' . $review->user->profile_picture) : asset('img/default-user.png') }}"
                         alt="{{ $review->user->name }}"
                         width="60" height="60"
                         style="object-fit: cover; border-radius: 10px;"
                         class="me-3 shadow-sm">
                    <div>
                        <h6 class="mb-0">{{ $review->user->name }}</h6>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <!-- Star Rating -->
                <div class="mb-2 text-warning">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fa fa-star {{ $i <= $review->rating ? '' : 'text-muted' }}"></i>
                    @endfor
                </div>

                <!-- Review Text -->
                <p class="text-dark mb-0">{{ \Illuminate\Support\Str::limit($review->review_text, 150) }}</p>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">No feedback available yet.</div>
        </div>
    @endforelse

    <!-- Leave Feedback Button -->
    <div class="col-12 text-center mt-3">
        <a href="#" class="btn btn-sm navbar-feedback-btn" data-bs-toggle="modal" data-bs-target="#feedbackModal">
            Leave Feedback
       </a>
    </div>

    <!-- Pagination -->
    @if ($recentReviews->hasPages())
        <div class="col-12 mt-4 mb-5">
            <div class="d-flex justify-content-center">
                {{ $recentReviews->onEachSide(1)->links() }}
            </div>
        </div>
    @endif
</div>


@endsection
