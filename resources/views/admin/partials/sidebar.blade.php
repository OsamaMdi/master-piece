<div class="sidebar" id="sidebar">
    <ul class="sidebar-links">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.users.index') }}" class="{{ Route::is('admin.users.index') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Users
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}" class="{{ Route::is('admin.products.index') ? 'active' : '' }}">
                <i class="fas fa-toolbox"></i> Products
            </a>
        </li>
        <li>
            <a href="{{ route('admin.reservations') }}"><i class="fas fa-calendar-check"></i> Reservations</a>
        </li>
        <li>
            <a href="{{ route('admin.categories.index') }}">
                <i class="fas fa-layer-group"></i> Categories
            </a>

        </li>

        <li>
            <a href="{{ route('admin.reviews.index') }}">
                <i class="fas fa-star-half-alt"></i> Product Reviews
            </a>
        </li>

        <li>
            <a href="{{ route('admin.website-reviews.index') }}">
                <i class="fas fa-comment-dots"></i> Website Reviews
            </a>
        </li>

        <li>
            <a href="{{ route('admin.reports.index') }}" class="{{ Route::is('admin.reports.*') ? 'active' : '' }}">
                <i class="fas fa-flag"></i> Reports
            </a>
        </li>

        <li>
            <a href="{{ route('admin.notifications.index') }}" class="{{ Route::is('admin.notifications.index') ? 'active' : '' }}">
                <i class="fas fa-bell"></i> Notifications
            </a>
        </li>



    </ul>
</div>
