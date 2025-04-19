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
            <a href="#"><i class="fas fa-cog"></i> Settings</a>
        </li>
    </ul>
</div>
