@extends('layouts.merchants.app')

@section('content')
<div class="dashboard-section py-4">
    <h2 class="page-title">Welcome, {{ Auth::user()->name }}</h2>

    <div class="metrics-cards-grid">
        <div class="metric-card h-100">
            <h4><i class="fas fa-box-open me-2 text-primary"></i>Active Products</h4>
            <p>{{ $totalActiveProducts }}</p>
        </div>

        <div class="metric-card h-100">
            <h4><i class="fas fa-ban me-2 text-danger"></i>Cancelled Reservations (This Month)</h4>
            <p>{{ $cancelledReservations }}</p>
        </div>

        <div class="metric-card h-100">
            <h4><i class="fas fa-star me-2 text-warning"></i>Average Rating</h4>
            <p>{{ number_format($averageRating, 2) }}/5</p>
        </div>

        <div class="metric-card h-100">
            <h4><i class="fas fa-money-bill-wave me-2 text-success"></i>Total Paid (This Month)</h4>
            <p>${{ number_format($totalPaid, 2) }}</p>
        </div>

        <div class="metric-card h-100">
            <h4><i class="fas fa-check-circle me-2 text-info"></i>Completed Reservations (This Month)</h4>
            <p>{{ $completedReservations }}</p>
        </div>

        <div class="metric-card h-100">
            <h4><i class="fas fa-percentage me-2 text-dark"></i>Platform Commission (This Month)</h4>
            <p>${{ number_format($platformCommission, 2) }}</p>
        </div>

        <div class="metric-card h-100">
            <h4><i class="fas fa-lock me-2 text-secondary"></i>Blocked Products</h4>
            <p>{{ $blockedProducts }}</p>
        </div>

        <div class="metric-card h-100">
            <h4><i class="fas fa-flag me-2 text-danger"></i>Reports on Your Products</h4>
            <p>{{ $productReports }}</p>
        </div>
    </div>

 <!-- ===== Monthly Comparison Cards (Charts Area Style) ===== -->
<div class="charts-grid mt-5">
    <div class="chart-box">
        <h5><i class="fas fa-calendar-alt me-2 text-primary"></i>Completed Reservations (Monthly)</h5>
        <canvas id="completedReservationsChart"></canvas>
    </div>


   <!-- ===== Combined Products Table ===== -->
<div class="top-products-table small-table mt-5">
    <h5 class="table-title">Top 5 Products by Rating & Reservations</h5>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Product</th>
                <th>Avg. Rating</th>
                <th>Reservations</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($topProductsCombined as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->avg_rating, 2) }}/5</td>
                    <td>{{ $product->completed_count }}</td>
                    <td>
                        <a href="{{ route('merchant.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No products available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="text-end">
        <a href="{{ route('merchant.products.index') }}" class="btn btn-sm btn-outline-secondary">View All Products</a>
    </div>
</div>
</div>





</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('completedReservationsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Last Month', 'This Month'],
            datasets: [{
                label: 'Completed Reservations',
                data: [{{ $completedLastMonth }}, {{ $completedThisMonth }}],
                backgroundColor: ['#e2e8f0', '#3182ce'],
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
});
</script>
@endpush
