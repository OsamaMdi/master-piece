<nav class="navbar">
    <!-- Left Section -->
    <div class="navbar-left">
        <button id="toggleSidebar" class="toggle-sidebar-btn">
            <i class="fas fa-bars"></i>
        </button>
        <a class="nav-brand" href="index.html">
            <img src="{{ asset('img/logof.png') }}" alt="Rentify Logo" class="logo-img">
        </a>
    </div>


    <!-- Center Section -->
    <div class="search-container position-relative" style="width: 600px;">
        <input type="text"
               id="adminSearchInput"
               class="search-input"
               placeholder="Search properties, users..."
               autocomplete="off">

        <button type="submit" class="search-btn">
            <i class="fas fa-search"></i>
        </button>
        <ul id="adminSearchResults" class="search-results"></ul>
    </div>

<div class="navbar-right d-flex align-items-center gap-4 position-relative">
    <!-- Notifications -->
    <div class="notification-badge position-relative">
        <i class="fas fa-bell fs-5 text-dark"></i>
        <span class="badge">5</span>
    </div>

    <!-- Profile Dropdown -->
    <div class="profile-dropdown position-relative">
        <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('img/default-user.png') }}"
             alt="Profile"
             class="rounded-circle"
             style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;"
             id="profileButton">

        <div class="dropdown-menu bg-white shadow-sm rounded py-2 px-2 position-absolute end-0 mt-2 d-none"
             id="profileMenu"
             style="min-width: 160px; z-index: 1052;">
            <a href="{{ route('profile') }}" class="dropdown-item py-2">👤 Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item py-2 w-100 text-start">🚪 Logout</button>
            </form>
        </div>
    </div>
</div>
</nav>
