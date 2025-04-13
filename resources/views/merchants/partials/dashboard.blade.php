@extends('layouts.merchants.app')

@section('content')
<div class="dashboard-overview">
    <h2 class="page-title">Welcome, {{ Auth::user()->name }}</h2>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">

        <div class="stat-card" style="background: #f9f9f9; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center;">
            <h3>Total Reservations</h3>
            <p style="font-size: 24px; font-weight: bold;">{{ $totalReservations }}</p>
        </div>

        <div class="stat-card" style="background: #f9f9f9; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center;">
            <h3>Total Tools</h3>
            <p style="font-size: 24px; font-weight: bold;">{{ $totalProducts }}</p>
        </div>

        <div class="stat-card" style="background: #f9f9f9; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center;">
            <h3>Rented Tools</h3>
            <p style="font-size: 24px; font-weight: bold;">{{ $rentedTools }}</p>
        </div>

    </div>
</div>
@endsection
