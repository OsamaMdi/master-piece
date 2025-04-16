<x-guest-layout>



    <!-- Register Form -->
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="register-form" class="auth-card relative">
        @csrf

        <!-- Name -->
        <div class="input-group">
            <label class="input-label" for="name">Name</label>
            <input id="name" class="input-field" type="text" name="name" required minlength="2" pattern="[A-Za-z ]+" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="input-group">
            <label class="input-label" for="email">Email</label>
            <input id="email" class="input-field" type="email" name="email" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="input-group">
            <label class="input-label" for="phone">Phone Number</label>
            <div class="flex">
                <span class="inline-flex items-center px-3 border border-r-0 rounded-l-md bg-gray-100 text-gray-600 text-sm">+962</span>
                <input id="phone" class="input-field rounded-none rounded-r-md border-gray-300" type="tel" name="phone" required pattern="[0-9]{8,10}" />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Identity Country -->
        <div class="input-group">
            <label class="input-label" for="identity_country">Country</label>
            <select id="identity_country" name="identity_country" class="input-field" required>
                <option value="">Select Country</option>
                <option value="Jordan">Jordan</option>
                <option value="Other">Other</option>
            </select>
            <x-input-error :messages="$errors->get('identity_country')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="input-group relative">
            <label class="input-label" for="register_password">Password</label>
            <input id="register_password" class="input-field pr-10" type="password" name="password" required minlength="8" />
            <button type="button" id="toggleRegisterPassword" aria-label="Show password" class="absolute right-3 top-14 transform -translate-y-1/2 text-gray-500 text-xl">
                <i id="toggleRegisterPasswordIcon" class="fa-regular fa-eye"></i>
            </button>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="input-group relative">
            <label class="input-label" for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" class="input-field pr-10" type="password" name="password_confirmation" required minlength="8" />
            <button type="button" id="toggleRegisterPasswordConfirm" aria-label="Show confirm password" class="absolute right-3 top-14 transform -translate-y-1/2 text-gray-500 text-xl">
                <i id="toggleRegisterPasswordConfirmIcon" class="fa-regular fa-eye"></i>
            </button>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Profile Picture -->
        <div class="input-group">
            <label class="input-label" for="profile_picture">Profile Picture</label>
            <input id="profile_picture" class="file-upload" type="file" name="profile_picture" />
            <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
        </div>

        <!-- Identity Image -->
        <div class="input-group">
            <label class="input-label" for="identity_image">Identity Image
                <div id="identity-upload-loader" class="hidden text-center mt-2">
                    <i class="fa fa-spinner fa-spin text-blue-500 text-2xl"></i>
                    <p class="text-sm text-gray-500 mt-1">Uploading ID...</p>
                </div>
        </label>
            <input id="identity_image" class="file-upload" type="file" name="identity_image" required />
            <x-input-error :messages="$errors->get('identity_image')" class="mt-2" />
        </div>

        <!-- User Type -->
        <div class="input-group">
            <label class="input-label" for="user_type">Register As</label>
            <select id="user_type" name="user_type" class="input-field" required>
                <option value="user" selected>User</option>
                <option value="merchant">Merchant</option>
            </select>
            <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
        </div>

        <!-- Hidden Inputs -->
        <input type="hidden" id="identity_number" name="identity_number">
        <input type="hidden" id="city" name="city">

        <!-- Submit Button -->
        <button class="primary-btn" type="submit">Register</button>

        <!-- Already registered Link -->
        <div class="flex justify-center mt-4">
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:underline">Already registered?</a>
        </div>
    </form>

    <!-- SweetAlert for Hidden Input Errors -->
    @php
        $hiddenErrors = [];
        if ($errors->has('identity_number')) {
            $hiddenErrors = array_merge($hiddenErrors, $errors->get('identity_number'));
        }
        if ($errors->has('city')) {
            $hiddenErrors = array_merge($hiddenErrors, $errors->get('city'));
        }
    @endphp

    @if (count($hiddenErrors))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $hiddenErrors) !!}`,
                timer: 5000,
                showConfirmButton: true
            });
        </script>
    @endif

</x-guest-layout>
