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
    <h2 class="section-title">Our Services</h2>
    <p class="section-subtitle">Explore the range of premium services we provide to enhance your experience.</p>

    <div class="container">
        <div class="service-content">
            @foreach ($categories as $category)
                <a href="{{ route('products.by.category', ['id' => $category->id]) }}"
                   class="single-service text-decoration-none"
                   style="
                       --main-color: {{ $category->color ?? '#ccc' }};
                       box-shadow: 0 4px 10px {{ $category->color ?? '#ccc' }}40;
                       border: 2px solid {{ $category->color ?? '#ccc' }};
                   ">

                    {{-- Icon --}}
                    @if($category->icon)
                        <div class="service-icon">
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
                    <h6>Testimonials</h6>
                    <h2>Our Guests Love Us</h2>
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




<!-- Testimonials Area End -->


<!-- Subscription Plans Section -->
<section class="subscription-plans">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Choose Your Plan</h2>
            <p class="section-subtitle">Flexible pricing for continuous access</p>
            <div class="billing-toggle">
                <span>Billed Monthly</span>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
                <span>Billed Yearly <em>(Save 20%)</em></span>
            </div>
        </div>

        <div class="plan-cards">
            <!-- Starter Plan -->
            <div class="plan-card">
                <div class="plan-header">
                    <h3 class="plan-name">Starter</h3>
                    <div class="plan-price">
                        <sup>$</sup>19<span>/mo</span>
                    </div>
                    <p class="plan-desc">Perfect for individual users</p>
                </div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> Single User Account</li>
                    <li><i class="fas fa-check"></i> Basic Analytics</li>
                    <li><i class="fas fa-check"></i> 5GB Storage</li>
                    <li><i class="fas fa-check"></i> Email Support</li>
                </ul>
                <button class="plan-cta">Start Free Trial</button>
                <div class="plan-notice">7-day free trial</div>
            </div>

            <!-- Professional Plan (Featured) -->
            <div class="plan-card featured">
                <div class="plan-badge">Most Popular</div>
                <div class="plan-header">
                    <h3 class="plan-name">Professional</h3>
                    <div class="plan-price">
                        <sup>$</sup>49<span>/mo</span>
                    </div>
                    <p class="plan-desc">For growing teams</p>
                </div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> Up to 5 Users</li>
                    <li><i class="fas fa-check"></i> Advanced Analytics</li>
                    <li><i class="fas fa-check"></i> 25GB Storage</li>
                    <li><i class="fas fa-check"></i> Priority Support</li>
                </ul>
                <button class="plan-cta">Start Free Trial</button>
                <div class="plan-notice">14-day free trial</div>
            </div>

            <!-- Enterprise Plan -->
            <div class="plan-card">
                <div class="plan-header">
                    <h3 class="plan-name">Enterprise</h3>
                    <div class="plan-price">
                        <sup>$</sup>99<span>/mo</span>
                    </div>
                    <p class="plan-desc">For large organizations</p>
                </div>
                <ul class="plan-features">
                    <li><i class="fas fa-check"></i> Unlimited Users</li>
                    <li><i class="fas fa-check"></i> Custom Analytics</li>
                    <li><i class="fas fa-check"></i> 100GB Storage</li>
                    <li><i class="fas fa-check"></i> 24/7 Support</li>
                </ul>
                <button class="plan-cta">Contact Sales</button>
                <div class="plan-notice">Custom plans available</div>
            </div>
        </div>

        <div class="plan-footer">
            <p>All plans include: <span>Secure hosting</span> • <span>Regular updates</span> • <span>99.9% uptime</span></p>
        </div>
    </div>
</section>

@endsection
