@extends('layouts.admin.app')

@section('content')
<div class="card card-body">
    <h2 class="form-title mb-4">✏️ Edit Category</h2>

    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-col">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-col">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-row mt-4">
            <div class="form-col">
                <label class="form-label">Color</label>
                <select id="colorSelect" name="color" class="form-select">
                    <option value="">Select a Color</option>
                    <option value="#dc3545" style="background-color: #dc3545; color: #fff;" {{ old('color', $category->color) == '#dc3545' ? 'selected' : '' }}>Red</option>
                    <option value="#fd7e14" style="background-color: #fd7e14; color: #000;" {{ old('color', $category->color) == '#fd7e14' ? 'selected' : '' }}>Orange</option>
                    <option value="#ffc107" style="background-color: #ffc107; color: #000;" {{ old('color', $category->color) == '#ffc107' ? 'selected' : '' }}>Yellow</option>
                    <option value="#198754" style="background-color: #198754; color: #fff;" {{ old('color', $category->color) == '#198754' ? 'selected' : '' }}>Green</option>
                    <option value="#0d6efd" style="background-color: #0d6efd; color: #fff;" {{ old('color', $category->color) == '#0d6efd' ? 'selected' : '' }}>Blue</option>
                    <option value="#6610f2" style="background-color: #6610f2; color: #fff;" {{ old('color', $category->color) == '#6610f2' ? 'selected' : '' }}>Indigo</option>
                    <option value="#6c757d" style="background-color: #6c757d; color: #fff;" {{ old('color', $category->color) == '#6c757d' ? 'selected' : '' }}>Gray</option>
                    <option value="#212529" style="background-color: #212529; color: #fff;" {{ old('color', $category->color) == '#212529' ? 'selected' : '' }}>Black</option>
                    <option value="#14532d" style="background-color: #14532d; color: #fff;" {{ old('color', $category->color) == '#14532d' ? 'selected' : '' }}>Dark Green</option>
                    <option value="#0dcaf0" style="background-color: #0dcaf0; color: #000;" {{ old('color', $category->color) == '#0dcaf0' ? 'selected' : '' }}>Light Blue</option>
                    <option value="#d97706" style="background-color: #d97706; color: #fff;" {{ old('color', $category->color) == '#d97706' ? 'selected' : '' }}>Dark Orange</option>
                    <option value="#6b8e23" style="background-color: #6b8e23; color: #fff;" {{ old('color', $category->color) == '#6b8e23' ? 'selected' : '' }}>Olive</option>
                    <option value="#4682b4" style="background-color: #4682b4; color: #fff;" {{ old('color', $category->color) == '#4682b4' ? 'selected' : '' }}>Steel Blue</option>
                    <option value="#dc143c" style="background-color: #dc143c; color: #fff;" {{ old('color', $category->color) == '#dc143c' ? 'selected' : '' }}>Crimson</option>
                    <option value="#daa520" style="background-color: #daa520; color: #000;" {{ old('color', $category->color) == '#daa520' ? 'selected' : '' }}>Goldenrod</option>
                    <option value="#8b4513" style="background-color: #8b4513; color: #fff;" {{ old('color', $category->color) == '#8b4513' ? 'selected' : '' }}>Saddle Brown</option>
                </select>
                @error('color')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            <div class="form-col">
                <label class="form-label">Icon</label>
                <select id="iconSelect" name="icon" class="form-select">
                    <option value="">Select an Icon</option>
                    <option value="fas fa-tools" {{ old('icon', $category->icon) == 'fas fa-tools' ? 'selected' : '' }}>Tools</option>
                    <option value="fas fa-broom" {{ old('icon', $category->icon) == 'fas fa-broom' ? 'selected' : '' }}>Cleaning</option>
                    <option value="fas fa-wrench" {{ old('icon', $category->icon) == 'fas fa-wrench' ? 'selected' : '' }}>Wrench</option>
                    <option value="fas fa-hammer" {{ old('icon', $category->icon) == 'fas fa-hammer' ? 'selected' : '' }}>Hammer</option>
                    <option value="fas fa-screwdriver" {{ old('icon', $category->icon) == 'fas fa-screwdriver' ? 'selected' : '' }}>Screwdriver</option>
                    <option value="fas fa-saw" {{ old('icon', $category->icon) == 'fas fa-saw' ? 'selected' : '' }}>Saw</option>
                    <option value="fas fa-lightbulb" {{ old('icon', $category->icon) == 'fas fa-lightbulb' ? 'selected' : '' }}>Lightbulb</option>
                    <option value="fas fa-plug" {{ old('icon', $category->icon) == 'fas fa-plug' ? 'selected' : '' }}>Plug</option>
                    <option value="fas fa-shield-alt" {{ old('icon', $category->icon) == 'fas fa-shield-alt' ? 'selected' : '' }}>Shield</option>
                    <option value="fas fa-leaf" {{ old('icon', $category->icon) == 'fas fa-leaf' ? 'selected' : '' }}>Leaf</option>
                    <option value="fas fa-hard-hat" {{ old('icon', $category->icon) == 'fas fa-hard-hat' ? 'selected' : '' }}>Helmet</option>
                    <option value="fas fa-truck" {{ old('icon', $category->icon) == 'fas fa-truck' ? 'selected' : '' }}>Truck</option>
                    <option value="fas fa-building" {{ old('icon', $category->icon) == 'fas fa-building' ? 'selected' : '' }}>Building</option>
                    <option value="fas fa-fire-extinguisher" {{ old('icon', $category->icon) == 'fas fa-fire-extinguisher' ? 'selected' : '' }}>Fire Extinguisher</option>
                    <option value="fas fa-battery-full" {{ old('icon', $category->icon) == 'fas fa-battery-full' ? 'selected' : '' }}>Battery Full</option>
                    <option value="fas fa-paint-roller" {{ old('icon', $category->icon) == 'fas fa-paint-roller' ? 'selected' : '' }}>Paint Roller</option>
                    <option value="fas fa-toolbox" {{ old('icon', $category->icon) == 'fas fa-toolbox' ? 'selected' : '' }}>Toolbox</option>
                    <option value="fas fa-camera" {{ old('icon', $category->icon) == 'fas fa-camera' ? 'selected' : '' }}>Photography</option>
                </select>
                @error('icon')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

        </div>

        <div class="mt-4 d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">← Cancel</a>
        </div>
    </form>
</div>
<script src="{{ asset('js/select2Category.js') }}"></script>
@endsection
