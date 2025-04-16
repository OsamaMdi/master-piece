<x-app-layout>

    <div class="profile-container">

        <!-- Page Title -->
        <div class="text-center mb-10">
            <div class="text-4xl font-bold text-gray-800">
                <span class="text-indigo-600">Edit</span> Profile
            </div>
        </div>

        <!-- Profile Edit Card -->
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-card" id="update-profile-form">
            @csrf
            @method('PATCH')

            <!-- Left Side: Avatar Upload + Email Display -->
            <div class="profile-left">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('img/default-user.png') }}"
                    alt="Profile Picture" class="profile-avatar">

                <input type="file" name="profile_picture" id="profile_picture" class="file-upload mt-4" accept="image/*">
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
                        <input type="text" name="name" id="name" class="input-field" value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- City -->
                    <div>
                        <label><i class="fas fa-city"></i> City</label>
                        <input type="text" name="city" id="city" class="input-field" value="{{ old('city', auth()->user()->city) }}" required>
                        @error('city')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div>
                        <label><i class="fas fa-flag"></i> Country</label>
                        <select name="identity_country" id="identity_country" class="input-field" required>
                            <option value="Jordan" {{ old('identity_country', auth()->user()->identity_country) == 'Jordan' ? 'selected' : '' }}>Jordan</option>
                            <option value="Other" {{ old('identity_country', auth()->user()->identity_country) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('identity_country')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label><i class="fas fa-map-marker-alt"></i> Address</label>
                        <textarea name="address" id="address" rows="3" class="input-field">{{ old('address', auth()->user()->address) }}</textarea>
                        @error('address')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label><i class="fas fa-phone"></i> Phone</label>
                        <input type="text" name="phone" id="phone" class="input-field" value="{{ old('phone', auth()->user()->phone) }}" required>
                        @error('phone')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between items-center gap-4 mt-8">
                    <!-- Back Button -->
                    <a href="javascript:history.back()" class="primary-btn flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>

                    <!-- Save Button -->
                    <button type="submit" class="primary-btn flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>

            </div>

        </form>

    </div>

    <!-- SweetAlert Validation -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('update-profile-form');

            form.addEventListener('submit', function (e) {
                let errors = [];

                const name = document.getElementById('name').value.trim();
                if (name.length < 2 || name.length > 50) {
                    errors.push('Name must be between 2 and 50 characters.');
                }

                const phone = document.getElementById('phone').value.trim();
                const jordanPhoneRegex = /^07[789]\d{7}$/;
                if (!jordanPhoneRegex.test(phone)) {
                    errors.push('Phone number must be a valid Jordanian number (e.g., 0791234567).');
                }

                const city = document.getElementById('city').value.trim();
                if (city.length < 2 || city.length > 50) {
                    errors.push('City must be between 2 and 50 characters.');
                }

                const country = document.getElementById('identity_country').value;
                if (country !== 'Jordan' && country !== 'Other') {
                    errors.push('Country must be either Jordan or Other.');
                }

                const address = document.getElementById('address').value.trim();
                if (address.length < 5 || address.length > 255) {
                    errors.push('Address must be between 5 and 255 characters.');
                }

                const profilePictureInput = document.getElementById('profile_picture');
                if (profilePictureInput.files.length > 0) {
                    const file = profilePictureInput.files[0];
                    const validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];

                    if (!validExtensions.includes(file.type)) {
                        errors.push('Profile picture must be a JPG or PNG file.');
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        errors.push('Profile picture size must be less than 2MB.');
                    }
                }

                if (errors.length > 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errors.join('<br>'),
                        confirmButtonColor: '#6366f1'
                    });
                }
            });

            @if (session('status') === 'profile-updated')
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: 'Your profile has been updated successfully!',
                    confirmButtonColor: '#6366f1'
                });
            @endif
        });
    </script>

</x-app-layout>
