<x-guest-layout>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="register-form" class="auth-card">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required minlength="2" pattern="[A-Za-z ]+" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <div class="flex">
                <span class="inline-flex items-center px-3 border border-r-0 rounded-l-md bg-gray-100 text-gray-600 text-sm">+962</span>
                <input id="phone" class="block w-full rounded-none rounded-r-md border-gray-300" type="tel" name="phone" required pattern="[0-9]{8,10}" />
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Identity Country -->
        <div class="mt-4">
            <x-input-label for="identity_country" :value="__('Country')" />
            <select id="identity_country" name="identity_country" class="block mt-1 w-full" required>
                <option value="">Select Country</option>
                <option value="Jordan">Jordan</option>
                <option value="Other">Other</option>
            </select>
            <x-input-error :messages="$errors->get('identity_country')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="relative mt-4">
            <x-input-label for="register_password" :value="__('Password')" />
            <x-text-input id="register_password" class="block mt-1 w-full pr-10" type="password" name="password" required minlength="8" />
            <button type="button" id="toggleRegisterPassword" aria-label="Show password" class="absolute right-3 top-10 transform -translate-y-1/2 text-gray-500 text-xl">
                <i id="toggleRegisterPasswordIcon" class="fa-regular fa-eye"></i>
            </button>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="relative mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10" type="password" name="password_confirmation" required minlength="8" />
            <button type="button" id="toggleRegisterPasswordConfirm" aria-label="Show confirm password" class="absolute right-3 top-10 transform -translate-y-1/2 text-gray-500 text-xl">
                <i id="toggleRegisterPasswordConfirmIcon" class="fa-regular fa-eye"></i>
            </button>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Profile Picture -->
        <div class="mt-4">
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            <input id="profile_picture" class="custom-file-input" type="file" name="profile_picture" />
            <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
        </div>

        <!-- Identity Image -->
        <div class="mt-4">
            <x-input-label for="identity_image" :value="__('Identity Image')" />
            <input id="identity_image" class="custom-file-input" type="file" name="identity_image" required />
            <x-input-error :messages="$errors->get('identity_image')" class="mt-2" />
        </div>

        <!-- User Type -->
        <div class="mt-4">
            <x-input-label for="user_type" :value="__('Register As')" />
            <select id="user_type" name="user_type" class="block mt-1 w-full" required>
                <option value="user" selected>User</option>
                <option value="merchant">Merchant</option>
            </select>
            <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
        </div>

        <!-- Hidden Inputs -->
        <input type="hidden" id="identity_number" name="identity_number">
        <input type="hidden" id="city" name="city">

        <!-- Submit Button -->
        <div class="flex items-center justify-end mt-6">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">Already registered?</a>
            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>
