@extends('layouts.admin.app')

@section('content')


<!-- ✅ Create Category Form (with Color and Icon Selection) -->
<div class="card card-body">
    <h2 class="form-title mb-4">➕ Add New Category</h2>

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        <div class="form-row">

            <div class="form-col">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="review-select review-search" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>


            <div class="form-col">
                <label class="form-label">Description</label>
                <textarea name="description" class="review-select review-search" rows="3"></textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-row mt-4">

            <div class="form-col">
                <label class="form-label">Color</label>
                <select id="colorSelect" name="color" class="form-select">
                    <option value="">Select a Color</option>
                    <option value="#dc3545" style="background-color: #dc3545; color: #fff;">Red</option>
                    <option value="#fd7e14" style="background-color: #fd7e14; color: #000;">Orange</option>
                    <option value="#ffc107" style="background-color: #ffc107; color: #000;">Yellow</option>
                    <option value="#198754" style="background-color: #198754; color: #fff;">Green</option>
                    <option value="#0d6efd" style="background-color: #0d6efd; color: #fff;">Blue</option>
                    <option value="#6610f2" style="background-color: #6610f2; color: #fff;">Indigo</option>
                    <option value="#6c757d" style="background-color: #6c757d; color: #fff;">Gray</option>
                    <option value="#212529" style="background-color: #212529; color: #fff;">Black</option>
                    <option value="#14532d" style="background-color: #14532d; color: #fff;">Dark Green</option>
                    <option value="#0dcaf0" style="background-color: #0dcaf0; color: #000;">Light Blue</option>
                    <option value="#d97706" style="background-color: #d97706; color: #fff;">Dark Orange</option>
                    <option value="#6b8e23" style="background-color: #6b8e23; color: #fff;">Olive</option>
                    <option value="#4682b4" style="background-color: #4682b4; color: #fff;">Steel Blue</option>
                    <option value="#dc143c" style="background-color: #dc143c; color: #fff;">Crimson</option>
                    <option value="#daa520" style="background-color: #daa520; color: #000;">Goldenrod</option>
                    <option value="#8b4513" style="background-color: #8b4513; color: #fff;">Saddle Brown</option>
                </select>
                @error('color')<small class="text-danger">{{ $message }}</small>@enderror
            </div>



            <div class="form-col">
                <label class="form-label">Icon</label>
                <select id="iconSelect" name="icon" class="form-select">
                    <option value="">Select an Icon</option>
                    <option value="fas fa-tools">Tools</option>
                    <option value="fas fa-broom">Cleaning</option>
                    <option value="fas fa-wrench">Wrench</option>
                    <option value="fas fa-hammer">Hammer</option>
                    <option value="fas fa-screwdriver">Screwdriver</option>
                    <option value="fas fa-saw">Saw</option>
                    <option value="fas fa-lightbulb">Lightbulb</option>
                    <option value="fas fa-plug">Plug</option>
                    <option value="fas fa-shield-alt">Shield</option>
                    <option value="fas fa-leaf">Leaf</option>
                    <option value="fas fa-hard-hat">Helmet</option>
                    <option value="fas fa-truck">Truck</option>
                    <option value="fas fa-building">Building</option>
                    <option value="fas fa-fire-extinguisher">Fire Extinguisher</option>
                    <option value="fas fa-battery-full">Battery Full</option>
                    <option value="fas fa-paint-roller">Paint Roller</option>
                    <option value="fas fa-toolbox">Toolbox</option>
                    <option value="fas fa-camera">Photography</option>
                </select>
                @error('icon')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

        </div>

        <div class="mt-4 d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Save Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">← Back</a>
        </div>
    </form>
</div>

<script src="{{ asset('js/select2Category.js') }}"></script>
@endsection
