@extends('layouts.user.app')

@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area bg-img bg-overlay jarallax" style="background-image: url('{{ asset('img/tool2.png') }}'); background-color: white;">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="breadcrumb-content text-center">
                        <h2 class="page-title">{{ $category->name ?? 'All Tools' }}</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tools</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <div class="roberto-rooms-area section-padding-100-0">
        <div class="container-fluid px-5">
            <div class="row">
                <!-- Sidebar Filter -->
                <div class="col-lg-2 mb-4">
                    <div class="filter-sidebar p-3 shadow-sm bg-white rounded" style="position: sticky; top: 100px;">
                        <h5 class="mb-3 text-start">Filter by Category</h5>
                        <ul class="list-unstyled text-start">
                            <li><a href="{{ route('tools.all') }}" class="d-block py-2 text-dark">All Tools</a></li>
                            @foreach ($categories as $cat)
                                <li>
                                    <a href="{{ route('products.by.category', $cat->id) }}"
                                       class="d-block py-2 text-dark {{ isset($category) && $category->id === $cat->id ? 'fw-bold text-primary' : '' }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Tools Cards -->
                <div class="col-lg-10">
                    <div class="row g-4">
                        @forelse($products as $product)
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="product-card wow fadeInUp" data-wow-delay="100ms">
                                    <div class="room-thumbnail">
                                        <img src="{{ $product->images->isNotEmpty()
                                        ? asset('storage/' . $product->images->sortByDesc('created_at')->first()->image_url)
                                        : asset('img/default-product.png') }}" alt="{{ $product->name }}">
                                        <div class="room-overlay">
                                            <a href="{{ route('user.products.show', $product->id) }}" class="view-detail-btn">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                        </div>
                                    </div>



                                    <div class="room-content">
                                        <h2>{{ $product->name }}</h2>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h4 class="m-0 text-primary">
                                                {{ number_format($product->price, 2) }} <span style="font-size: 0.8rem;">/ Day</span>
                                            </h4>
                                            <span class="badge bg-light text-dark border">{{ $product->category->name ?? 'N/A' }}</span>
                                        </div>

                                        <div class="room-feature d-flex flex-wrap gap-3">
                                            <p class="mb-0 w-50"><strong>Status:</strong> {{ ucfirst($product->status) }}</p>
                                            <p class="mb-0 w-50"><strong>Quantity:</strong> {{ $product->quantity }}</p>
                                        </div>

                                        <div class="rating mt-3">
                                            @php
                                                $avgRating = $product->reviews->avg('rating');
                                                $filledStars = floor($avgRating);
                                            @endphp
                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < $filledStars)
                                                    <i class="icon_star" style="color: #f1c40f;"></i>
                                                @else
                                                    <i class="icon_star" style="color: #ccc;"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center">No tools found{{ isset($category) ? ' in this category' : '' }}.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <nav class="roberto-pagination wow fadeInUp my-5" data-wow-delay="1000ms">
                            <ul class="pagination justify-content-center">
                                {{ $products->links() }}
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
