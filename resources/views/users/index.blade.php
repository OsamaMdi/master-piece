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
        <div class="single-welcome-slide bg-img bg-overlay" style="background-image: url('{{ asset('img/tool2.png') }}');" data-img-url="{{ asset('img/tool2.png') }}">
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
        <div class="single-welcome-slide bg-img bg-overlay" style="background-image: url('{{ asset('img/tool3.png') }}');" data-img-url="{{ asset('img/tool3.png') }}">
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

<section class="roberto-service-area">
    <h2 class="section-title">Our Services</h2>
    <p class="section-subtitle">Explore the range of premium services we provide to enhance your experience.</p>

    <div class="container">
      <div class="service-content">

        <div class="single-service">
          <span class="service-count">1</span>
          <img src="https://cdn-icons-png.flaticon.com/512/69/69524.png" alt="Transportation">
          <h5>Transportation</h5>
        </div>

        <div class="single-service">
          <span class="service-count">2</span>
          <img src="https://cdn-icons-png.flaticon.com/512/201/201623.png" alt="Room Service">
          <h5>Room Service</h5>
        </div>

        <div class="single-service">
          <span class="service-count">3</span>
          <img src="https://cdn-icons-png.flaticon.com/512/2909/2909762.png" alt="Spa Relaxation">
          <h5>Spa Relaxation</h5>
        </div>

        <div class="single-service">
          <span class="service-count">4</span>
          <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Restaurant">
          <h5>Restaurant</h5>
        </div>

        <div class="single-service">
          <span class="service-count">5</span>
          <img src="https://cdn-icons-png.flaticon.com/512/2947/2947992.png" alt="Bar & Drinks">
          <h5>Bar & Drinks</h5>
        </div>

      </div>
    </div>
  </section>

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
