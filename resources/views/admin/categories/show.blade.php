@extends('layouts.admin.app')

@section('content')
<!-- ======== Page Title ======== -->
<div class="product-page-header">
    <h1 class="page-title">{{ $category->name }}</h1>
</div>

<!-- ======== Category Card with Image + Details Inside ======== -->
<div class="product-card-container">
    <div class="product-details-card">

        <!-- Category Image Side -->
        <div class="product-image-side">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('img/default-category.jpg') }}"
                             alt="{{ $category->name }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Information Side -->
        <div class="product-info-side">
            <h3>Description:</h3>
            <p>{{ $category->description ?? 'No description provided.' }}</p>

            <h3>Color:</h3>
            <p>
                <span style="display: inline-block; background: {{ $category->color }}; color: white; padding: 0.25rem 0.75rem; border-radius: 5px;">
                    {{ $category->color }}
                </span>
            </p>

            <h3>Icon:</h3>
            <p><i class="{{ $category->icon }}"></i> {{ $category->icon }}</p>

            <h3>Created At:</h3>
            <p>{{ $category->created_at->format('Y-m-d H:i') }}</p>

            <!-- Back Button -->
            <div class="mt-4 text-end" style="margin-top: 2rem;">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">← Back</a>
            </div>
        </div>
    </div>
</div>

@endsection
