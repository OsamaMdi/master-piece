<nav class="navbar flex items-center justify-between p-4 bg-white shadow">
    <!-- Left Section: Sidebar Toggle + Logo -->
    <div class="navbar-left flex items-center space-x-4">
        <!-- Toggle Sidebar Button -->
        <button id="toggleSidebar" class="toggle-sidebar-btn text-gray-600 text-2xl focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Logo -->
        <a class="nav-brand" href="index.html">
            <img src="{{ asset('img/logof.png') }}" alt="Rentify Logo" class="logo-img">
        </a>

    </div>

    <!-- Center Section: Search -->
    <div class="navbar-center hidden md:flex flex-1 justify-center">
        <input type="text" class="search-input w-1/2 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-indigo-500" placeholder="Search...">
    </div>

    <!-- Right Section: Notifications + Profile -->
    <div class="nav-right flex items-center space-x-6">
        <!-- Notifications -->
        <div class="relative">
            <i class="fas fa-bell text-gray-600 text-2xl"></i>
            <span class="absolute -top-1 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">5</span>
        </div>

        <!-- Profile Dropdown -->
        <div class="relative">
            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://i.pravatar.cc/40' }}"
                 alt="Profile"
                 class="profile-pic w-10 h-10 rounded-full cursor-pointer object-cover"
                 id="profileButton">

            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-50" id="profileMenu">
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">👤 Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    <button type="submit" class="w-full text-left block px-4 py-2 text-gray-700 hover:bg-gray-100">🚪 Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>
