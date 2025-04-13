@extends('layouts.user.app')

@section('content')

<!-- Welcome Area Start -->
<section class="welcome-area">
    <div class="welcome-slides owl-carousel">
        <!-- Single Welcome Slide -->
        <div class="single-welcome-slide bg-img bg-overlay" style="background-image: url('{{ asset('img/bg-img/16.jpg') }}');" data-img-url="{{ asset('img/bg-img/16.jpg') }}">
            <!-- Welcome Content -->
            <div class="welcome-content h-100">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12">
                            <div class="welcome-text text-center">
                                <h6 data-animation="fadeInLeft" data-delay="200ms">Hotel &amp; Resort</h6>
                                <h2 data-animation="fadeInLeft" data-delay="500ms">Welcome To Roberto</h2>
                                <a href="#" class="btn roberto-btn btn-2" data-animation="fadeInLeft" data-delay="800ms">Discover Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Welcome Slide -->
        <div class="single-welcome-slide bg-img bg-overlay" style="background-image: url('{{ asset('img/bg-img/17.jpg') }}');" data-img-url="{{ asset('img/bg-img/17.jpg') }}">
            <div class="welcome-content h-100">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12">
                            <div class="welcome-text text-center">
                                <h6 data-animation="fadeInUp" data-delay="200ms">Hotel &amp; Resort</h6>
                                <h2 data-animation="fadeInUp" data-delay="500ms">Welcome To Roberto</h2>
                                <a href="#" class="btn roberto-btn btn-2" data-animation="fadeInUp" data-delay="800ms">Discover Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Welcome Slide -->
        <div class="single-welcome-slide bg-img bg-overlay" style="background-image: url('{{ asset('img/bg-img/18.jpg') }}');" data-img-url="{{ asset('img/bg-img/18.jpg') }}">
            <div class="welcome-content h-100">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12">
                            <div class="welcome-text text-center">
                                <h6 data-animation="fadeInDown" data-delay="200ms">Hotel &amp; Resort</h6>
                                <h2 data-animation="fadeInDown" data-delay="500ms">Welcome To Roberto</h2>
                                <a href="#" class="btn roberto-btn btn-2" data-animation="fadeInDown" data-delay="800ms">Discover Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- Welcome Area End -->

<!-- Service Area Start -->
<div class="roberto-service-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="service-content d-flex align-items-center justify-content-between">
                    <!-- Single Service Area -->
                    <div class="single-service--area mb-100 wow fadeInUp" data-wow-delay="100ms">
                        <img src="{{ asset('img/core-img/icon-1.png') }}" alt="">
                        <h5>Transportion</h5>
                    </div>

                    <!-- Single Service Area -->
                    <div class="single-service--area mb-100 wow fadeInUp" data-wow-delay="300ms">
                        <img src="{{ asset('img/core-img/icon-2.png') }}" alt="">
                        <h5>Reiseservice</h5>
                    </div>

                    <!-- Single Service Area -->
                    <div class="single-service--area mb-100 wow fadeInUp" data-wow-delay="500ms">
                        <img src="{{ asset('img/core-img/icon-3.png') }}" alt="">
                        <h5>Spa Relaxtion</h5>
                    </div>

                    <!-- Single Service Area -->
                    <div class="single-service--area mb-100 wow fadeInUp" data-wow-delay="700ms">
                        <img src="{{ asset('img/core-img/icon-4.png') }}" alt="">
                        <h5>Restaurant</h5>
                    </div>

                    <!-- Single Service Area -->
                    <div class="single-service--area mb-100 wow fadeInUp" data-wow-delay="900ms">
                        <img src="{{ asset('img/core-img/icon-1.png') }}" alt="">
                        <h5>Bar &amp; Drink</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Service Area End -->


<!-- Testimonials Area Start -->
<section class="roberto-testimonials-area section-padding-100-0">
    <div class="container">
        <div class="row align-items-center">

         
            <div class="col-12 col-md-6">
                <div class="testimonial-thumbnail owl-carousel mb-100">
                    <img src="{{ asset('img/bg-img/10.jpg') }}" alt="">
                    <img src="{{ asset('img/bg-img/11.jpg') }}" alt="">
                </div>
            </div>

            <!-- محتوى التستيمونيال (الجهة اليمنى) -->
            <div class="col-12 col-md-6">
                <!-- Section Heading -->
                <div class="section-heading">
                    <h6>Testimonials</h6>
                    <h2>Our Guests Love Us</h2>
                </div>

                <!-- Testimonial Slide -->
                <div class="testimonial-slides owl-carousel mb-100">

                    <!-- Testimonial 1 -->
                    <div class="single-testimonial-slide">
                        <h5>“This can be achieved by applying search engine optimization or popularly known as SEO...”</h5>
                        <div class="rating-title">
                            <div class="rating">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="icon_star"></i>
                                @endfor
                            </div>
                            <h6>Robert Downey <span>- CEO Deercreative</span></h6>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="single-testimonial-slide">
                        <h5>“Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus delectus...”</h5>
                        <div class="rating-title">
                            <div class="rating">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="icon_star"></i>
                                @endfor
                            </div>
                            <h6>Akash Downey <span>- CEO Deercreative</span></h6>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="single-testimonial-slide">
                        <h5>“Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolor, ex quos...”</h5>
                        <div class="rating-title">
                            <div class="rating">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="icon_star"></i>
                                @endfor
                            </div>
                            <h6>Downey Sarah <span>- CEO Deercreative</span></h6>
                        </div>
                    </div>

                    <!-- Testimonial 4 -->
                    <div class="single-testimonial-slide">
                        <h5>“Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore sequi laboriosam...”</h5>
                        <div class="rating-title">
                            <div class="rating">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="icon_star"></i>
                                @endfor
                            </div>
                            <h6>Robert Brown <span>- CEO Deercreative</span></h6>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Testimonials Area End -->


<!-- Blog Area Start -->
<section class="roberto-blog-area section-padding-100-0">
    <div class="container">
        <div class="row">
            <!-- Section Heading -->
            <div class="col-12">
                <div class="section-heading text-center wow fadeInUp" data-wow-delay="100ms">
                    <h6>Our Blog</h6>
                    <h2>Latest News &amp; Event</h2>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- Single Blog Post -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="single-post-area mb-100 wow fadeInUp" data-wow-delay="300ms">
                    <a href="#" class="post-thumbnail">
                        <img src="{{ asset('img/bg-img/2.jpg') }}" alt="">
                    </a>
                    <div class="post-meta">
                        <a href="#" class="post-date">Jan 02, 2019</a>
                        <a href="#" class="post-catagory">Event</a>
                    </div>
                    <a href="#" class="post-title">Learn How To Motivate Yourself</a>
                    <p>How many free autoresponders have you tried? And how many emails did you get through using them?</p>
                    <a href="#" class="btn continue-btn"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>

            <!-- Single Blog Post -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="single-post-area mb-100 wow fadeInUp" data-wow-delay="500ms">
                    <a href="#" class="post-thumbnail">
                        <img src="{{ asset('img/bg-img/3.jpg') }}" alt="">
                    </a>
                    <div class="post-meta">
                        <a href="#" class="post-date">Jan 02, 2019</a>
                        <a href="#" class="post-catagory">Event</a>
                    </div>
                    <a href="#" class="post-title">What If Let You Run The Hubble</a>
                    <p>My point here is that if you have no clue for the answers above you probably are not operating a followup.</p>
                    <a href="#" class="btn continue-btn"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>

            <!-- Single Blog Post -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="single-post-area mb-100 wow fadeInUp" data-wow-delay="700ms">
                    <a href="#" class="post-thumbnail">
                        <img src="{{ asset('img/bg-img/4.jpg') }}" alt="">
                    </a>
                    <div class="post-meta">
                        <a href="#" class="post-date">Jan 02, 2019</a>
                        <a href="#" class="post-catagory">Event</a>
                    </div>
                    <a href="#" class="post-title">Six Pack Abs The Big Picture</a>
                    <p>Some good steps to take to ensure you are getting what you need out of a autoresponder include…</p>
                    <a href="#" class="btn continue-btn"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Blog Area End -->

@endsection
