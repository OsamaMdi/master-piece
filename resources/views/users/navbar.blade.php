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
                        <div class="classynav d-flex justify-content-between align-items-center w-100" style="height: 80px;">

                            <!-- Desktop Left Section -->
                            <div class="d-none d-lg-flex align-items-center gap-3">
                                <!-- Left Nav Links -->
                                <ul id="nav" class="d-flex align-items-center gap-3 mb-0">
                                    <li class="active"><a href="{{ route('home') }}">Home</a></li>
                                    <li><a href="{{ route('tools.all') }}">All Tools</a></li>
                                    <li><a href="{{ route('about') }}">About Us</a></li>

                                    @auth
                                    <li class="position-relative">
                                        <a href="#">Categories</a>
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

                                <!-- Feedback -->
                                @auth
                                @if (auth()->user()->user_type === 'user')
                                <a href="#" class="navbar-feedback-btn" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                                    Leave Feedback
                                </a>
                                @endif
                                @endauth

                                <!-- Chat -->
                                @auth
                                <div class="chat-wrapper position-relative">
                                    <a href="{{ route('chat.index') }}" class="text-decoration-none position-relative">
                                        <i class="fas fa-comment-dots fs-5 text-dark"></i>
                                        <span id="chat-unread-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill d-none">
                                            0
                                        </span>
                                    </a>
                                </div>
                                @endauth

                                <!-- Search -->
                                <div class="search-btn">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div> <!-- End Desktop Left Section -->

                            <!-- Mobile Menu Content -->
                            <div class="mobile-menu-content d-lg-none w-100">
                                <ul class="mobile-nav mb-4">
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li><a href="{{ route('tools.all') }}">All Tools</a></li>
                                    <li><a href="{{ route('about') }}">About Us</a></li>
                                    @auth
                                  <li class="position-relative">
  <a href="#">Categories</a>
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

                                <div class="mobile-actions d-flex flex-column gap-3">
                                    @auth
                                    @if (auth()->user()->user_type === 'user')
                                    <a href="#" class="navbar-feedback-btn" data-bs-toggle="modal" data-bs-target="#feedbackModal">Leave Feedback</a>
                                    @endif
                                    <div class="chat-wrapper">
                                        <a href="{{ route('chat.index') }}" class="text-decoration-none">
                                            <i class="fas fa-comment-dots me-2"></i>Chat
                                        </a>
                                    </div>
                                    @endauth
                                    <div class="search-btn">
                                        <i class="fa fa-search me-2"></i>Search
                                    </div>
                                </div>
                            </div> <!-- End Mobile Menu Content -->

                            <!-- Right Section -->
                            <div class="profile-dropdown d-flex align-items-center gap-2" style="height: 100%; margin-left: 30px;">
                                @auth
                                <img
                                    id="profileButton"
                                    src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://i.pravatar.cc/40' }}"
                                    alt="Profile"
                                    style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; cursor: pointer;"
                                >
                                <span class="fw-semibold">{{ Auth::user()->name }}</span>

                                <!-- Dropdown Menu -->
                                <div id="profileMenu" class="dropdown-content hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow z-[9999]">

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
                                @endauth

                                @guest
                                <div class="d-flex gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login</a>
                                    <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Register</a>
                                </div>
                                @endguest
                            </div> <!-- End Right Section -->

                        </div> <!-- End classynav -->
                    </div> <!-- End classy-menu -->
                </nav> <!-- End nav -->
            </div> <!-- End container -->
        </div> <!-- End classy-nav-container -->
    </div> <!-- End main-header-area -->
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
