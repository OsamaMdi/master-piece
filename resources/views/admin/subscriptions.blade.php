@extends('layouts.admin.app')

@section('content')
<h2 class="page-title mb-4">💳 Active Subscriptions</h2>

<!-- Filter/Search bar (optional) -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <form method="GET" class="review-filter-form d-flex flex-wrap gap-3 m-0">
        <input type="text" name="search" class="review-select review-search" placeholder="Search by merchant name or plan..." value="{{ request('search') }}">
        <button type="submit" class="review-filter-btn">🔍 Filter</button>
    </form>
</div>

@if($subscribers->count())
<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Merchant</th>
            <th>Plan</th>
            <th>Price</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($subscribers as $subscriber)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $subscriber->name }}<br><small>{{ $subscriber->email }}</small></td>
            <td>{{ $subscriber->subscription->subscription_type ?? '—' }}</td>
            <td>{{ $subscriber->subscription->price }} JOD</td>
            <td>{{ \Carbon\Carbon::parse($subscriber->subscription->start_date)->format('Y-m-d') }}</td>
            <td>{{ \Carbon\Carbon::parse($subscriber->subscription->end_date)->format('Y-m-d') }}</td>
            <td class="text-center">
              @php
    $end = \Carbon\Carbon::parse($subscriber->subscription->end_date);
    $isActive = now()->lte($end); // ✅ نشط إذا اليوم <= end_date
    $days = $end->diffInDays(now(), false); // فرق الأيام
@endphp

@if($isActive)
    <div class="active-subscription p-2 rounded" style="font-weight: bold; display: inline-block; min-width: 150px;">
        Active – {{ $days }} days left
    </div>
@else
    <div class="inactive-subscription p-2 rounded" style="font-weight: bold; display: inline-block; min-width: 150px;">
        Expired – {{ abs($days) }} days ago
    </div>
@endif

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $subscribers->withQueryString()->links() }}
</div>
@else
    <div class="alert alert-info">No active subscriptions found.</div>
@endif
@endsection
