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
                    <a class="nav-brand" href="index.html">
                        <img src="./img/logof.png" alt="Rentify Logo" class="logo-img">
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
                        <div class="classynav d-flex align-items-center">
                            <ul id="nav" class="me-4">
                                <li class="active"><a href="{{ route('index') }}">Home</a></li>
                                <li><a href="{{ route('room') }}">Rooms</a></li>
                                <li><a href="{{ route('about') }}">About Us</a></li>
                                <li><a href="#">Pages</a>
                                    <ul class="dropdown">
                                        <li><a href="{{ route('index') }}">- Home</a></li>
                                        <li><a href="{{ route('room') }}">- Rooms</a></li>
                                        <li><a href="{{ route('single-room') }}">- Single Rooms</a></li>
                                        <li><a href="{{ route('about') }}">- About Us</a></li>
                                        <li><a href="{{ route('contact') }}">- Contact</a></li>
                                        <li><a href="#">- Dropdown</a>
                                            <ul class="dropdown">
                                                <li><a href="#">- Dropdown Item</a></li>
                                                <li><a href="#">- Dropdown Item</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>

                            <!-- Search -->
                            <div class="search-btn me-3">
                                <i class="fa fa-search"></i>
                            </div>

                            <!-- Profile Dropdown -->
                            <div class="profile-dropdown position-relative" style="margin-left:17px">
                                <img
                                    id="profileButton"
                                    src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://i.pravatar.cc/40' }}"
                                    alt="Profile"
                                    class="profile-pic"
                                >
                                <div class="dropdown-menu" id="profileMenu">
                                    <a href="{{ route('profile') }}">👤 Profile</a>
                                    <a href="{{-- {{ route('help') }} --}}">❓ Help</a> <!-- ✅ تم إضافة خيار المساعدة هنا -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit">🚪 Logout</button>
                                    </form>
                                </div>
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


