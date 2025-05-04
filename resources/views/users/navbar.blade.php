<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Title -->
    <title>Roberto - Hotel &amp; Resort HTML Template</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/block.css') }}">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap 5 JS (with Popper) -->
   {{--  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
          .hidden {
        display: none !important;
    }
        #searchFormInput::placeholder {
            color: #e9dede;
            opacity: 1;
        }
    </style>

@stack('styles')
</head>

<body>
<!-- User Search -->
    <span id="isUserAuthenticated" data-auth="{{ auth()->check() ? 'true' : 'false' }}"></span>


<!-- Header Area Start -->
<header class="header-area">
    <!-- Search Form -->
    <div class="search-form d-flex align-items-center">
        <div class="container">
            <form id="searchForm" onsubmit="return false;" class="position-relative">
                <input type="search" id="searchFormInput" placeholder="Search for a product or category" autocomplete="off">
                <button type="submit"><i class="icon_search"></i></button>
                <ul id="liveSearchResults" class="list-group position-absolute w-100 bg-white shadow mt-1" style="z-index: 9999; display: none;"></ul>
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
                                <li><a href="{{ route('tools.all') }}">All Tools</a></li>
                                <li><a href="{{ route('about') }}">About Us</a></li>
                                @auth
                                <li><a href="#">Categories</a>
                                    <ul class="dropdown">
                                        @foreach($categories as $category)
                                            <li>
                                                <a href="{{ route('products.by.category', ['id' => $category->id]) }}">
                                                    - {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endauth

                                <li><a href="{{ route('contact') }}">Contact</a></li>

                            </ul>


                           <!-- Right Side Auth & Search -->
<div class="d-flex align-items-center ms-auto" style="margin-right: 25px">

    @auth
    @if (auth()->user()->user_type === 'user')
        <!-- Feedback Button -->
        <div class="me-3">
            <a href="#" class="btn btn-sm navbar-feedback-btn" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                Leave Feedback
            </a>
        </div>
    @endif
@endauth


    <!-- Search Icon -->
    <div class="search-btn me-3">
        <i class="fa fa-search"></i>
    </div>

    @auth
    <div class="profile-dropdown position-relative" style="display: inline-block;">
        <img
            id="profileButton"
            src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://i.pravatar.cc/40' }}"
            alt="Profile"
            style="cursor: pointer; width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"
        >
        <div id="profileMenu" class="dropdown-content hidden" style="
            position: absolute;
            right: 0;
            top: 110%;
            background: white;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-radius: 6px;
            min-width: 200px;
            padding: 10px;
            z-index: 1000;
        ">
            <a href="{{ route('profile') }}" style="display: block;width: 100%; padding: 8px;">👤 Profile</a>


            @if (Auth::user()->user_type === 'admin')
                <a href="{{ route('admin.dashboard') }}" style="display: block; width: 100%; padding: 8px;">🔧 Admin Dashboard</a>
            @elseif (Auth::user()->user_type === 'merchant')
                <a href="{{ route('merchant.dashboard') }}" style="display: block; width: 100%;  padding: 8px;">🛒 Merchant Dashboard</a>
            @endif

            @if (auth()->user()->user_type === 'user')
            <a href="{{ route('user.activity')  }} " style="display: block; width: 100%; padding: 8px;">📋 My Activity</a>
                <button type="button" data-bs-toggle="modal"  data-bs-target="#generalReportModal"
                    style="
                        display: block;
                        width: 100%;
                        padding: 8px;
                        text-align: left;
                        background: none;
                        border: none;
                        color: inherit;
                        font: inherit;
                        text-decoration: none;
                        cursor: pointer;
                    ">
                    🚩 Report
                </button>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="margin: 0; padding: 0;">
                @csrf
                <button type="submit"
                    style="
                        display: block;
                        width: 100%;
                        padding: 8px;
                        text-align: left;
                        background: none;
                        border: none;
                        color: inherit;
                        font: inherit;
                        text-decoration: none;
                        cursor: pointer;
                    ">
                    🚪 Logout
                </button>
            </form>
        </div>
    </div>
    @endauth


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





                            {{--  General Report Modal --}}

                            <div class="modal fade" id="generalReportModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('reservations.report', 0) }}">
                                        @csrf
                                        <input type="hidden" name="type" value="general">
                                        <input type="hidden" name="target_id" value="">

                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">📝 General Report</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <p class="mb-2 text-muted">
                                                    Please describe the issue or feedback you want to report about the platform or experience.
                                                </p>

                                                <div class="mb-3">
                                                    <label for="subject" class="form-label">Subject (Optional)</label>
                                                    <input type="text" name="subject" class="form-control" placeholder="e.g. Bug in booking system">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="message" class="form-label">Describe the issue</label>
                                                    <textarea name="message" class="form-control" rows="4" required placeholder="Please explain the problem..."></textarea>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-danger">Submit Report</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
