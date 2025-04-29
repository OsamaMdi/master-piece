@extends('layouts.user.app')

@section('content')

<!-- Welcome Area Start -->
<section class="welcome-area">
    <div class="welcome-slides owl-carousel">
        <!-- Single Welcome Slide -->
        <div class="single-welcome-slide bg-img bg-overlay" style="background-image: url('{{ asset('img/tool1.png') }}');" data-img-url="{{ asset('img/tool1.jpg') }}">
            <!-- Welcome Content -->
            <div class="welcome-content h-100">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12">
                            <div class="welcome-text text-center">
                                <h6 data-animation="fadeInLeft" data-delay="200ms">Tools & Rentals</h6>
                                <h2 data-animation="fadeInLeft" data-delay="500ms">Welcome to Rentify</h2>
                                <a href="{{ route('tools.all') }}" class="btn roberto-btn btn-2" data-animation="fadeInLeft" data-delay="800ms">Browse All Tools</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Welcome Slide -->
        <div class="single-welcome-slide bg-img bg-overlay" style="background-image: url('{{ asset('img/tool2.png') }}');" data-img-url="{{ asset('img/tool2.png') }}">
            <div class="welcome-content h-100">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12">
                            <div class="welcome-text text-center">
                                <h6 data-animation="fadeInLeft" data-delay="200ms">Tools & Rentals</h6>
                                <h2 data-animation="fadeInLeft" data-delay="500ms">Welcome to Rentify</h2>
                                <a href="{{ route('tools.all') }}" class="btn roberto-btn btn-2" data-animation="fadeInLeft" data-delay="800ms">Browse All Tools</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Welcome Slide -->
        <div class="single-welcome-slide bg-img bg-overlay" style="background-image: url('{{ asset('img/tool3.png') }}');" data-img-url="{{ asset('img/tool3.png') }}">
            <div class="welcome-content h-100">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12">
                            <div class="welcome-text text-center">
                                <h6 data-animation="fadeInLeft" data-delay="200ms">Tools & Rentals</h6>
                                <h2 data-animation="fadeInLeft" data-delay="500ms">Welcome to Rentify</h2>
                                <a href="{{ route('tools.all') }}" class="btn roberto-btn btn-2" data-animation="fadeInLeft" data-delay="800ms">Browse All Tools</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Welcome Area End -->
<section class="roberto-service-area">
    <h2 class="section-title">Categories</h2>
    <p class="section-subtitle">Choose from our available categories to explore our offerings.</p>

    <div class="container">
        <div class="service-content">
            @foreach ($categories as $category)
                <a href="{{ route('products.by.category', ['id' => $category->id]) }}"
                   class="single-service text-decoration-none">

                    {{-- Icon --}}
                    @if($category->icon)
                        <div class="service-icon" style="color: {{ $category->color ?? '#ccc' }};">
                            <i class="{{ $category->icon }}"></i>
                        </div>
                    @endif

                    {{-- Name --}}
                    <h5>{{ $category->name }}</h5>

                    {{-- Description --}}
                    @if($category->description)
                        <p class="service-description">
                            {{ Str::limit($category->description, 100) }}
                        </p>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
<!-- Testimonials Area Start -->


@if($reviews->isNotEmpty())
<section class="roberto-testimonials-area section-padding-100-0">
    <div class="container">
        <div class="row align-items-center">

            <!-- الصور الجانبية -->
            <div class="col-12 col-md-6">
                <div class="testimonial-thumbnail owl-carousel mb-100 text-center">
                    @foreach($reviews as $review)
                        <img src="{{ $review->user->profile_picture
                                        ? asset('storage/' . $review->user->profile_picture)
                                        : asset('img/default-user.png') }}"
                             onerror="this.onerror=null; this.src='{{ asset('img/default-user.png') }}';"
                             alt="{{ $review->user->name }}">
                    @endforeach
                </div>
            </div>

            <!-- التقييمات -->
            <div class="col-12 col-md-6">
                <div class="section-heading">
                    <h6>
                        <a href="{{ route('about') }}" style="text-decoration: none; color: inherit;">
                            Customer Feedback
                        </a>
                    </h6>
                    <h2>What Our Clients Are Saying</h2>
                </div>

                <div class="testimonial-slides owl-carousel mb-100">
                    @foreach($reviews as $review)
                        <div class="single-testimonial-slide">
                            <h5>"{{ $review->review_text }}"</h5>

                            <!-- التقييم -->
                            <div class="rating mb-1">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <i class="icon_star"></i>
                                @endfor
                            </div>

                            <!-- الاسم تحت التقييم -->
                            <h6 class="mt-1">{{ $review->user->name ?? 'Anonymous' }}</h6>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
@endif




@endsection
