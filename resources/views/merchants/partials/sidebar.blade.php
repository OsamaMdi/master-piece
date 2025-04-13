<div class="sidebar" id="sidebar">
    <ul class="sidebar-links">
        <li>
            <a href="{{ route('merchant.dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('merchant.products.index') }}" class="{{ Route::is('merchant.products.index') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Products
            </a>
        </li>
        <li>
            <a href="#"><i class="fas fa-chart-line"></i> Charts</a>
        </li>
        <li>
            <a href="#"><i class="fas fa-cog"></i> Settings</a>
        </li>
    </ul>
</div>
