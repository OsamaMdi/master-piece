<x-app-layout>

    <div class="profile-container">

        <!-- Page Title -->
        <div class="text-center mb-10">
            <div class="text-4xl font-bold text-gray-800">
                <span class="text-indigo-600">Edit</span> Profile
            </div>
        </div>

        <!-- Profile Edit Card -->
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-card"
            id="update-profile-form" novalidate>
            @csrf
            @method('PATCH')

            <!-- Left Side: Avatar Upload -->
            <div class="profile-left">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('img/default-user.png') }}"
                    alt="Profile Picture" class="profile-avatar">

                <input type="file" name="profile_picture" id="profile_picture" class="file-upload mt-4"
                    accept="image/jpeg,image/jpg,image/png,image/webp">
                @error('profile_picture')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Right Side: Editable Fields -->
            <div class="profile-right">
                <h2 class="profile-name">Update Your Information</h2>

                <div class="profile-info">
                    <!-- Name -->
                    <div>
                        <label><i class="fas fa-user"></i> Name</label>
                        <input type="text" name="name" id="name" class="input-field"
                            value="{{ old('name', auth()->user()->name) }}" required minlength="2" maxlength="50">
                        @error('name')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" id="email" class="input-field"
                            value="{{ old('email', auth()->user()->email) }}" required
                            pattern="^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|hotmail\.com)$">
                        @error('email')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label><i class="fas fa-phone"></i> Phone</label>
                        <input type="text" name="phone" id="phone" class="input-field"
                            value="{{ old('phone', auth()->user()->phone) }}" required pattern="^07[789]\d{7}$">
                        @error('phone')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address (now full width) -->
                    <div class="col-span-2">
                        <label><i class="fas fa-map-marker-alt"></i> Address</label>
                        <textarea name="address" id="address" rows="4" class="input-field w-full" required minlength="5"
                            maxlength="255">{{ old('address', auth()->user()->address) }}</textarea>
                        @error('address')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Identity Image -->
                    <div>
                        <label><i class="fas fa-id-card"></i> Identity Image</label>
                        <input type="file" name="identity_image" id="identity_image" class="file-upload"
                            accept="image/jpeg,image/jpg,image/png,image/webp">
                        @error('identity_image')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remove Profile Picture Checkbox (moved below) -->
                    <div class="mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="remove_profile_picture" value="1" class="mr-2"> Remove
                            current profile picture
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between items-center gap-4 mt-8">
                    <a href="javascript:history.back()" class="primary-btn flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="primary-btn flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- Scripts -->
    <meta name="upload-id-route" content="{{ route('users.upload.identity') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/identity-check.js') }}"></script>
</x-app-layout>
