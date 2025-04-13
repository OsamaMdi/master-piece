<x-guest-layout>

    <form method="POST" action="{{ route('login') }}" id="login-form" class="auth-card">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password with Eye Icon -->
        <div class="relative mt-4">
            <x-input-label for="login_password" :value="__('Password')" />
            <x-text-input id="login_password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="current-password" />
            <button type="button" id="toggleLoginPassword" aria-label="Show password" class="absolute right-3 top-10 transform -translate-y-1/2 text-gray-500 text-xl">
                <i id="toggleLoginPasswordIcon" class="fa-regular fa-eye"></i>
            </button>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ml-4">
                {{ __('Login') }}
            </x-primary-button>
        </div>

        <!-- Additional Links -->
        <div class="flex justify-between mt-6 text-sm">
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Create Account</a>
            <a href="{{ route('password.request') }}" class="text-gray-600 hover:underline">Forgot Password?</a>
        </div>
    </form>

</x-guest-layout>
