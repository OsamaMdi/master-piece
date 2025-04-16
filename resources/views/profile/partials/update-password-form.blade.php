<x-app-layout>

    <div class="profile-container">

        <div class="profile-section">

          <!-- Page Title -->
<div class="text-center mb-10">
    <div class="text-4xl font-bold text-gray-800 dark:text-black">
        <span class="text-indigo-600">Change</span> Password
    </div>
    <p class="mt-2 text-gray-500">Ensure your account is using a strong password.</p> <!-- هذا الوصف خليه إذا بدك -->
</div>

            <!-- Change Password Form -->
            <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <!-- Current Password -->
                <div class="input-group">
                    <x-input-label for="current_password" :value="__('Current Password')" />
                    <x-text-input id="current_password" name="current_password" type="password" class="input-field" autocomplete="current-password" required />
                    <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
                </div>

                <!-- New Password -->
                <div class="input-group">
                    <x-input-label for="password" :value="__('New Password')" />
                    <x-text-input id="password" name="password" type="password" class="input-field" autocomplete="new-password" required />
                    <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
                </div>

                <!-- Confirm New Password -->
                <div class="input-group">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="input-field" autocomplete="new-password" required />
                    <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
                </div>

                <!-- Buttons -->
                <div class="flex justify-between mt-8">
                    <!-- Back Button -->
                    <button type="button" onclick="history.back()" class="primary-btn flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>

                    <!-- Save Button -->
                    <button type="submit" class="primary-btn flex items-center gap-2">
                        <i class="fas fa-save"></i> Update Password
                    </button>
                </div>

            </form>

            <!-- SweetAlert if password updated -->
            @if (session('status') === 'password-updated')
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Updated!',
                        text: 'Your password has been changed successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                </script>
            @endif

        </div>

    </div>

</x-app-layout>
