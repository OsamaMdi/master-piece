@extends('layouts.merchants.app')

@section('content')
<style>
    .modal-content {
        margin: auto;
    }
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .form-col {
        flex: 1;
        min-width: 250px;
    }
</style>

<div class="container py-4">
    <div class="card p-4">

        <!-- Header -->
        <div class="custom-grid gap-2">
            <div class="flex-grow-1">
                <h2 class="m-0">📄 My Subscription</h2>
            </div>
        </div>

        <!-- Subscription Info -->
        <div class="form-row mt-4">
            <div class="form-col">
                <p><strong>Subscription Type:</strong> {{ $activeSubscription->subscription_type }}</p>
            </div>
            <div class="form-col">
                <p><strong>Price:</strong> ${{ number_format($activeSubscription->price, 2) }}</p>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($activeSubscription->start_date)->format('Y-m-d') }}</p>
            </div>
            <div class="form-col">
                <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($activeSubscription->end_date)->format('Y-m-d') }}</p>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <p><strong>Created At:</strong> {{ $activeSubscription->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <div class="text-end mt-4">
            <a href="{{ route('merchant.dashboard') }}" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    </div>
</div>
@endsection

