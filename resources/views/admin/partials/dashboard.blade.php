@extends('layouts.merchants.app')

@section('content')
<div class="dashboard-overview">
    <h2 class="page-title">Welcome, {{ Auth::user()->name }}</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Reservations</h3>
            <p class="stat-value">{{ $totalReservations }}</p>
        </div>

        <div class="stat-card">
            <h3>Total Tools</h3>
            <p class="stat-value">{{ $totalProducts }}</p>
        </div>

        <div class="stat-card">
            <h3>Rented Tools</h3>
            <p class="stat-value">{{ $rentedTools }}</p>
        </div>
    </div>
</div>
@endsection
