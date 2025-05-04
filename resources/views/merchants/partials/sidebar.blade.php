<div class="sidebar" id="sidebar">
    <ul class="sidebar-links">
        <li>
            <a href="{{ route('merchant.dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('merchant.products.index') }}" class="{{ Route::is('merchant.products.index') ? 'active' : '' }}">
                <i class="fas fa-toolbox"></i> Products
            </a>
        </li>
        <li>
            <a href="{{ route('merchant.reservations') }}"><i class="fas fa-calendar-check"></i> Reservations</a>
        </li>

        <li>
            <a href="{{ route('merchant.notifications.index') }}" class="{{ Route::is('merchants.notifications.index') ? 'active' : '' }}">
                <i class="fas fa-bell"></i> Notifications
            </a>
        </li>


    </ul>
</div>
