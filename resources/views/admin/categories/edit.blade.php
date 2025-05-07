@extends('layouts.admin.app')

@section('content')
<div class="card card-body">
    <h2 class="form-title mb-4">✏️ Edit Category</h2>

    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-col">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-col">
                <label class="form-label">Category Image</label>
                <input type="file" name="image" accept="image/*" class="form-control">
                @error('image') <small class="text-danger">{{ $message }}</small> @enderror

                @if($category->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="Current Image" style="max-height: 120px;" class="rounded border">
                    </div>
                @endif
            </div>
        </div>

        <div class="form-row mt-4">
            <div class="form-col">
                <label class="form-label">Color</label>
                <select id="colorSelect" name="color" class="form-select">
                    <option value="">Select a Color</option>
                    @foreach([
                        '#dc3545' => 'Red', '#fd7e14' => 'Orange', '#ffc107' => 'Yellow', '#198754' => 'Green',
                        '#0d6efd' => 'Blue', '#6610f2' => 'Indigo', '#6c757d' => 'Gray', '#212529' => 'Black',
                        '#14532d' => 'Dark Green', '#0dcaf0' => 'Light Blue', '#d97706' => 'Dark Orange',
                        '#6b8e23' => 'Olive', '#4682b4' => 'Steel Blue', '#dc143c' => 'Crimson',
                        '#daa520' => 'Goldenrod', '#8b4513' => 'Saddle Brown'
                    ] as $colorCode => $label)
                        <option value="{{ $colorCode }}" style="background-color: {{ $colorCode }}; color: {{ in_array($colorCode, ['#ffc107', '#fd7e14', '#daa520', '#0dcaf0']) ? '#000' : '#fff' }}"
                            {{ old('color', $category->color) == $colorCode ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('color')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="form-col">
                <label class="form-label">Icon</label>
                <select id="iconSelect" name="icon" class="form-select">
                    <option value="">Select an Icon</option>
                    @foreach([
                        'fas fa-tools' => 'Tools', 'fas fa-broom' => 'Cleaning', 'fas fa-wrench' => 'Wrench',
                        'fas fa-hammer' => 'Hammer', 'fas fa-screwdriver' => 'Screwdriver', 'fas fa-saw' => 'Saw',
                        'fas fa-lightbulb' => 'Lightbulb', 'fas fa-plug' => 'Plug', 'fas fa-shield-alt' => 'Shield',
                        'fas fa-leaf' => 'Leaf', 'fas fa-hard-hat' => 'Helmet', 'fas fa-truck' => 'Truck',
                        'fas fa-building' => 'Building', 'fas fa-fire-extinguisher' => 'Fire Extinguisher',
                        'fas fa-battery-full' => 'Battery Full', 'fas fa-paint-roller' => 'Paint Roller',
                        'fas fa-toolbox' => 'Toolbox', 'fas fa-camera' => 'Photography'
                    ] as $icon => $label)
                        <option value="{{ $icon }}" {{ old('icon', $category->icon) == $icon ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('icon')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>

        <div class="form-col mt-4">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">← Cancel</a>
            <button type="submit" class="btn btn-primary">Update Category</button>
        </div>
    </form>
</div>

<script src="{{ asset('js/select2Category.js') }}"></script>
@endsection
