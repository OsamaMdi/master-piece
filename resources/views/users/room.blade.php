@extends('layouts.user.app')

@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area bg-img bg-overlay jarallax" style="background-image: url(img/bg-img/16.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="breadcrumb-content text-center">
                        <h2 class="page-title">Our Room</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Room</li>
                            </ol>
                        </nav>
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
                <!-- Change to Grid Layout -->
                <div class="col-12">
                    <div class="row">
                        <!-- Single Product -->
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="single-room-area wow fadeInUp" data-wow-delay="100ms">
                                <div class="room-thumbnail position-relative">
                                    <img src="img/bg-img/43.jpg" alt="">
                                    <div class="room-overlay">
                                        <a href="#" class="view-detail-btn">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                                <div class="room-content text-center">
                                    <h2>Hammer for Rent</h2>
                                    <h4>$50 <span>/ Day</span></h4>
                                    <div class="room-feature">
                                        <p><strong>Size:</strong> Large</p>
                                        <p><strong>Capacity:</strong> Max Weight: 10kg</p>
                                        <p><strong>Type:</strong> Heavy Duty</p>
                                        <p><strong>Services:</strong> Delivery available, Wifi</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Product -->
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="single-room-area wow fadeInUp" data-wow-delay="300ms">
                                <div class="room-thumbnail position-relative">
                                    <img src="img/bg-img/44.jpg" alt="">
                                    <div class="room-overlay">
                                        <a href="#" class="view-detail-btn">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                                <div class="room-content text-center">
                                    <h2>Electric Drill</h2>
                                    <h4>$30 <span>/ Day</span></h4>
                                    <div class="room-feature">
                                        <p><strong>Size:</strong> Medium</p>
                                        <p><strong>Capacity:</strong> Max Speed: 2500 RPM</p>
                                        <p><strong>Type:</strong> Cordless</p>
                                        <p><strong>Services:</strong> Tool Kit available, Wifi</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Product -->
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="single-room-area wow fadeInUp" data-wow-delay="500ms">
                                <div class="room-thumbnail position-relative">
                                    <img src="img/bg-img/45.jpg" alt="">
                                    <div class="room-overlay">
                                        <a href="#" class="view-detail-btn">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                                <div class="room-content text-center">
                                    <h2>Generator for Rent</h2>
                                    <h4>$100 <span>/ Day</span></h4>
                                    <div class="room-feature">
                                        <p><strong>Power:</strong> 5 kW</p>
                                        <p><strong>Fuel:</strong> Diesel</p>
                                        <p><strong>Type:</strong> Portable</p>
                                        <p><strong>Services:</strong> Delivery available</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <nav class="roberto-pagination wow fadeInUp mb-100" data-wow-delay="1000ms">
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next <i class="fa fa-angle-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Rooms Area End -->
@endsection

