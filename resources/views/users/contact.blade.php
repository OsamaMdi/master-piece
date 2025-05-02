@extends('layouts.user.app')

@section('content')

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area contact-breadcrumb bg-img bg-overlay jarallax" style="background-image: url('{{ asset('img/tool3.png') }}');">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content text-center mt-100">
                    <h2 class="page-title">Contact Us</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->

<!-- Google Maps & Contact Info Area Start -->
<section class="google-maps-contact-info">
    <div class="container-fluid">
        <div class="google-maps-contact-content">
            <div class="row">
                <div class="col-6 col-lg-3">
                    <div class="single-contact-info">
                        <i class="fa fa-phone"></i>
                        <h4>Phone</h4>
                        <p>0788863725</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="single-contact-info">
                        <i class="fa fa-map-marker"></i>
                        <h4>Address</h4>
                        <p>Amman, Jordan</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="single-contact-info">
                        <i class="fa fa-clock-o"></i>
                        <h4>Working Hours</h4>
                        <p>9:00 AM - 8:00 PM</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="single-contact-info">
                        <i class="fa fa-envelope-o"></i>
                        <h4>Email</h4>
                        <p>info@rentify.com</p>
                    </div>
                </div>
            </div>

            <div class="google-maps">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3385.287904239169!2d35.90428237556655!3d31.953949224029422!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151b5f85ed1cf347%3A0xc3f2a4d59d0be229!2sAmman%2C%20Jordan!5e0!3m2!1sen!2sjo!4v1713790000000"
                    width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen=""
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>
<!-- Google Maps & Contact Info Area End -->

<!-- Contact Form Area Start -->
<div class="roberto-contact-form-area section-padding-100">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading text-center wow fadeInUp" data-wow-delay="100ms">
                    <h6>Contact Us</h6>
                    <h2>Leave Message</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="roberto-contact-form">

                    @if (session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    {{-- Route will be enabled later --}}
                    <form method="POST" action="{{ route('contact.send') }}">
                        {{-- action="{{ route('contact.send') }}" --}}
                        @csrf
                        <div class="row">
                            @guest
                                <div class="col-12 col-lg-6 wow fadeInUp" data-wow-delay="100ms">
                                    <input type="text" name="name" class="form-control mb-30" placeholder="Your Name" required>
                                </div>
                                <div class="col-12 col-lg-6 wow fadeInUp" data-wow-delay="100ms">
                                    <input type="email" name="email" class="form-control mb-30" placeholder="Your Email" required>
                                </div>
                            @else
                                <div class="col-12 mb-3 text-center text-md-start">
                                    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                                </div>
                            @endguest

                            <div class="col-12 wow fadeInUp" data-wow-delay="100ms">
                                <textarea name="message" class="form-control mb-30" placeholder="Your Message" required></textarea>
                            </div>

                            <div class="col-12 text-center wow fadeInUp" data-wow-delay="100ms">
                                <button type="submit" class="btn roberto-btn mt-15">Send Message</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Form Area End -->

@endsection
