<!DOCTYPE html>
<html lang="en">

<head>

   
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Title -->
    <title>Roberto - Hotel &amp; Resort HTML Template</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap 5 JS (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@stack('styles')
</head>

<body>

<!-- Header Area Start -->
<header class="header-area">
    <!-- Search Form -->
    <div class="search-form d-flex align-items-center">
        <div class="container">
            <form action="index.html" method="get">
                <input type="search" name="search-form-input" id="searchFormInput" placeholder="Type your keyword ...">
                <button type="submit"><i class="icon_search"></i></button>
            </form>
        </div>
    </div>

    <!-- Main Header Start -->
    <div class="main-header-area">
        <div class="classy-nav-container breakpoint-off">
            <div class="container">
                <!-- Classy Menu -->
                <nav class="classy-navbar justify-content-between align-items-center" id="robertoNav">

                    <!-- Logo -->
                    <a class="nav-brand" href="{{ route('home') }}">
                        <img src="{{ asset('img/logof.png') }}" alt="Rentify Logo" class="logo-img">
                    </a>

                    <!-- Navbar Toggler -->
                    <div class="classy-navbar-toggler">
                        <span class="navbarToggler"><span></span><span></span><span></span></span>
                    </div>

                    <!-- Menu -->
                    <div class="classy-menu">
                        <!-- Close Button -->
                        <div class="classycloseIcon">
                            <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                        </div>

                        <!-- Nav Start -->
                        <div class="classynav d-flex align-items-center w-100">
                            <!-- Left Side Nav -->
                            <ul id="nav" class="me-auto">
                                <li class="active"><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('tools.all') }}">Rooms</a></li>
                                <li><a href="{{ route('user.feedback') }}">About Us</a></li>
                                <li><a href="#">Pages</a>
                                    <ul class="dropdown">
                                        <li><a href="{{ route('home') }}">- Home</a></li>
                                        <li><a href="{{ route('tools.all') }}">- Tools</a></li>
                                        <li><a href="{{ route('user.feedback') }}">- About Us</a></li>
                                        <li><a href="{{ route('contact') }}">- Contact</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>


                           <!-- Right Side Auth & Search -->
<div class="d-flex align-items-center ms-auto" style="margin-right: 25px">

    <!-- Feedback Button -->
    <div class="me-3">
        <a href="#" class="btn btn-sm navbar-feedback-btn" data-bs-toggle="modal" data-bs-target="#feedbackModal">
            Leave Feedback
        </a>
    </div>

    <!-- Search Icon -->
    <div class="search-btn me-3">
        <i class="fa fa-search"></i>
    </div>

    <!-- Authenticated User -->
    @auth
        <div class="profile-dropdown position-relative">
            <img
                id="profileButton"
                src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://i.pravatar.cc/40' }}"
                alt="Profile"
                class="profile-pic"
                style="cursor: pointer;"
            >
            <div class="dropdown-menu dropdown-menu-end" id="profileMenu" style="display: none; position: absolute; right: 0; top: 110%;">
                <a href="{{ route('profile') }}">👤 Profile</a>
                <a href="#">❓ Help</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">🚪 Logout</button>
                </form>
            </div>
        </div>
    @endauth

    <!-- Guest User -->
    @guest
        <div style="margin-left: 15px;">
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login</a>
            <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Register</a>
        </div>
    @endguest

</div>

                        </div>
                        <!-- Nav End -->
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- Header Area End -->


