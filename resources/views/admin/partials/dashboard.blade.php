@extends('layouts.admin.app')

@section('content')
@php use Illuminate\Support\Str; @endphp


<div class="dashboard-section py-4">
    <h2 class="page-title">Welcome, {{ Auth::user()->name }}</h2>


     <!-- ===== Metrics Cards ===== -->
<div class="metrics-cards-grid row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
    <div class="col">
        <div class="metric-card h-100">
            <h4><i class="fas fa-calendar-check me-2 text-primary"></i> Total Reservations</h4>
            <p>{{ $totalReservations }}</p>
        </div>
    </div>
    <div class="col">
        <div class="metric-card h-100">
            <h4><i class="fas fa-toolbox me-2 text-success"></i> Total Products</h4>
            <p>{{ $totalProducts }}</p>
        </div>
    </div>
    <div class="col">
        <div class="metric-card h-100">
            <h4><i class="fas fa-coins me-2 text-success"></i> Platform Profit ({{ $currentMonthName }})</h4>
            <p>{{ number_format($currentMonthProfit, 2) }} JOD</p>
        </div>
    </div>
    <div class="col">
        <div class="metric-card h-100">
            <h4><i class="fas fa-flag me-2 text-danger"></i> Pending Reports</h4>
            <p>{{ $pendingReports }}</p>
        </div>
    </div>
    <div class="col">
        <div class="metric-card h-100">
            <h4><i class="fas fa-users me-2 text-info"></i> Total Users</h4>
            <p>{{ $totalUsers }}</p>
        </div>
    </div>
    <div class="col">
        <div class="metric-card h-100">
            <h4><i class="fas fa-store me-2 text-dark"></i> Total Merchants</h4>
            <p>{{ $totalMerchants }}</p>
        </div>
    </div>
    <div class="col">
        <div class="metric-card h-100">
            <h4><i class="fas fa-coins me-2 text-success"></i> Total Revenue</h4>
            <p>{{ number_format($totalRevenue, 2) }} JOD</p>
        </div>
    </div>
    <div class="col">
        <div class="metric-card h-100">
            <h4><i class="fas fa-star me-2 text-warning"></i> Average Reviews</h4>
            <p>{{ $averageRating }}</p>
        </div>
    </div>
</div>


    <!-- ===== Charts Area ===== -->
<div class="charts-grid mt-5">
    <div class="chart-box">
        <h5><i class="fas fa-user-friends me-2 text-primary"></i>Users Growth (Week vs. Last Week)</h5>
        <canvas id="usersGrowthChart"></canvas>
    </div>
    <div class="chart-box">
        <h5><i class="fas fa-boxes me-2 text-success"></i>Products Growth</h5>
        <canvas id="productsGrowthChart"></canvas>
    </div>
    <div class="chart-box">
        <h5><i class="fas fa-calendar-day me-2 text-info"></i>Monthly Reservations Comparison</h5>
        <canvas id="dailyReservationsChart"></canvas>
    </div>

    <div class="chart-box">
        <h5><i class="fas fa-tags me-2 text-warning"></i>Reservations by Category</h5>
        <canvas id="reservationsByCategoryChart"></canvas>
    </div>
</div>


    <!-- ===== Tables Area ===== -->
    <div class="top-items-tables mt-5">
        <div class="top-users-table small-table">
            <h5 class="table-title">Top 5 Users by Reservations</h5>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Reservations</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topUsers as $user)
                    <tr>
                        <td>{{ Str::before($user->name, ' ') }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->reservations_count }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">View All Users</a>
            </div>
        </div>

        <div class="top-products-table small-table">
            <h5 class="table-title">Top 5 Highest-Rated Products</h5>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Owner</th>
                        <th>Avg Rating</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topRatedProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->owner_name }}</td>
                        <td>{{ number_format($product->average_rating, 1) }}/5</td>
                        <td>
                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">View All Products</a>
            </div>
        </div>
    </div>
</div>


<script>
      const reservationsByCategoryData = {
        labels: @json($reservationsByCategory['labels']),
        data: @json($reservationsByCategory['data'])
    };
    console.log(reservationsByCategoryData);
    document.addEventListener('DOMContentLoaded', function () {
        // 🧑‍🤝‍🧑 Users Growth Chart
        new Chart(document.getElementById('usersGrowthChart'), {
            type: 'bar',
            data: {
                labels: ['Last Week', 'This Week'],
                datasets: [{
                    label: 'Users',
                    data: [{{ $usersLastWeek }}, {{ $usersThisWeek }}],
                    backgroundColor: ['#cbd5e0', '#4a6cf7'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });

        // 🛠 Products Growth Chart
        new Chart(document.getElementById('productsGrowthChart'), {
            type: 'bar',
            data: {
                labels: ['Last Week', 'This Week'],
                datasets: [{
                    label: 'Products',
                    data: [{{ $productsLastWeek }}, {{ $productsThisWeek }}],
                    backgroundColor: ['#cbd5e0', '#38a169'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });



        new Chart(document.getElementById('dailyReservationsChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyReservationComparison['labels']) !!},
        datasets: [
            {
                label: 'Current Month',
                data: {!! json_encode($monthlyReservationComparison['currentMonth']) !!},
                borderColor: '#4a6cf7',
                backgroundColor: 'rgba(74,108,247,0.2)',
                tension: 0.3,
                fill: true,
                pointRadius: 4
            },
            {
                label: 'Last Month',
                data: {!! json_encode($monthlyReservationComparison['lastMonth']) !!},
                borderColor: '#f56565',
                backgroundColor: 'rgba(245,101,101,0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 4
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});



        // 📊 Reservations by Category Chart
        new Chart(document.getElementById('reservationsByCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($reservationsByCategory['labels']) !!},
                datasets: [{
                    data: {!! json_encode($reservationsByCategory['data']) !!},
                    backgroundColor: ['#4a6cf7', '#38a169', '#f6ad55', '#e53e3e', '#805ad5']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
    </script>

@endsection


@push('scripts')
<script src="{{ asset('js/adminChart.js') }}"></script>
@endpush
