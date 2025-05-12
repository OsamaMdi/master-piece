@extends('layouts.merchants.app')

@section('content')

<!-- ✅ Page Title -->
<h2 class="page-title mb-4">🛠️ Your Tools</h2>

<!-- ✅ Add New Product Button (now links to create page) -->
<div class="filter-header">
    <a href="{{ route('merchant.products.create') }}" class="btn btn-success force-right">
        ➕ Add New Product
    </a>
</div>

<!-- ✅ Table for Products -->
@if($products->count())
    <table class="table table-bordered mt-4" style="margin-top: 6%;">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price (JOD/day)</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                <td>
                    @if($product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->images->sortByDesc('created_at')->first()->image_url) }}" alt="{{ $product->name }}" class="product-img">
                    @else
                        <img src="{{ asset('img/logo.png') }}" alt="No Image" class="product-img">
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->quantity }}</td>
                @php
                    $statusClass = match($product->status) {
                        'available' => 'custom-status available',
                        'blocked' => 'custom-status blocked',
                        'maintenance' => 'custom-status maintenance',
                        default => 'custom-status unknown'
                    };
                @endphp
                <td>
                    <span class="{{ $statusClass }}">
                        {{ ucfirst($product->status ?? 'Unknown') }}
                    </span>
                </td>
                <td class="text-center" style="display: flex; gap: 8px; justify-content: center;">
                    <a href="{{ route('merchant.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('merchant.products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{ route('merchant.products.destroy', $product->id) }}" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" style="width: 38px; height: 33px;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-info mt-4" style="margin-top: 6%;">
        No products found. Start by adding your first tool!
    </div>
@endif

<!-- ✅ Notification Popups -->
@if(session('success'))
<script>
    showCustomNotification("{{ session('success') }}", "success");
</script>
@endif

@if(session('error'))
<script>
    showCustomNotification("{{ session('error') }}", "error");
</script>
@endif

{{-- <!-- ✅ Custom Notification Component -->
<div id="customNotification" class="custom-notification hidden">
    <button id="closeCustomNotification" class="close-btn">×</button>
    <div class="icon" id="notificationIcon"></div>
    <div class="message" id="customNotificationMessage"></div>
    <div class="progress-bar" id="customProgressBar"></div>
</div> --}}

<!-- ✅ Pagination Section -->
@if ($products->lastPage() > 1)
    <div class="pagination-container mt-4">
        <ul class="pagination justify-content-center">
            @for ($i = 1; $i <= $products->lastPage(); $i++)
                <li class="page-item {{ ($products->currentPage() == $i) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
        </ul>
    </div>
@endif
{{--
<!-- ======== Bootstrap Confirmation Modal ======== -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this product?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelDelete">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>
 --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const types = ['success', 'error', 'warning', 'info'];

        types.forEach(type => {
            const message = sessionStorage.getItem(type);
            if (message) {
                Swal.fire({
                    icon: type,
                    title: type.charAt(0).toUpperCase() + type.slice(1) + '!',
                    text: message,
                    timer: 2500,
                    showConfirmButton: false
                });
                sessionStorage.removeItem(type);
            }
        });
    });
</script>

@endsection
