<nav class="navbar">
    <!-- Left Section -->
    <div class="navbar-left">
        <button id="toggleSidebar" class="toggle-sidebar-btn">
            <i class="fas fa-bars"></i>
        </button>
        <a class="nav-brand" href="{{ route('merchant.dashboard') }}">
            <img src="{{ asset('img/logof.png') }}" alt="Rentify Logo" class="logo-img">
        </a>
    </div>

    <!-- Center Section -->
    <div class="search-container position-relative" style="width: 600px;">
        <input type="text"
               id="merchantSearchInput"
               class="search-input"
               placeholder="Search tools, reservations..."
               autocomplete="off">

        <button type="submit" class="search-btn">
            <i class="fas fa-search"></i>
        </button>

        <ul id="merchantSearchResults" class="search-results"></ul>
    </div>

    <div class="navbar-right d-flex align-items-center gap-4 position-relative">

<!-- Chat Icon -->
<div class="notification-wrapper position-relative">
    <a href="{{ route('merchant.chat.index') }}" class="text-decoration-none position-relative">
        <i class="fas fa-comment-dots fs-5 text-dark" style="color:black;"></i>
        <span id="chat-unread-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill d-none">
            0
        </span>
    </a>
</div>

        <!-- Notifications -->
<div class="notification-wrapper position-relative" id="notification-wrapper">
    <i class="fas fa-bell fs-5 text-dark" style="color:black;"></i>

    @if ($unreadCount > 0)
        <span class="badge" id="notification-count">{{ $unreadCount }}</span>
    @else
        <span class="badge d-none" id="notification-count" >0</span>
    @endif

    <div class="notification-dropdown position-absolute bg-white rounded shadow-sm" id="notification-dropdown">
        <ul id="notification-list" class="list-unstyled m-0 p-2 small">
            @forelse ($notifications as $notification)
                <li>
                    <a href="{{ $notification->url ?? '#' }}" class="d-block text-decoration-none py-1 px-2 {{ !$notification->is_read ? 'fw-bold' : '' }}">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-bell me-2 text-primary"></i>
                            <div class="flex-grow-1">
                                {{ $notification->message }}
                                <div class="text-muted small">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li><div class="text-center text-muted small py-2">No notifications yet</div></li>
            @endforelse
        </ul>
    </div>
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

                <a href="{{ route('home') }}" class="dropdown-item py-2">🏠 Home</a>

                <a href="{{ route('merchant.reports.mine') }}" class="dropdown-item py-2">🚩 My Reports</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="dropdown-item py-2 text-start w-100"
                        style="background: none; border: none; font-weight: bold; font-size: 1.1rem; width: 100%;">
                        🚪 Logout
                    </button>
                </form>

            </div>

        </div>

    </div>
</nav>
