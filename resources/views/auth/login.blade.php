<x-guest-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" id="login-form" class="auth-card relative">
        @csrf

        <!-- Email -->
        <div class="input-group">
            <label class="input-label" for="email">Email</label>
            <input id="email" class="input-field" type="email" name="email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password with Eye Icon -->
        <div class="input-group relative">
            <label class="input-label" for="login_password">Password</label>
            <input id="login_password" class="input-field pr-10" type="password" name="password" required autocomplete="current-password" />
            <button type="button" id="toggleLoginPassword" aria-label="Show password" class="absolute right-3 top-14 transform -translate-y-1/2 text-gray-500 text-xl">
                <i id="toggleLoginPasswordIcon" class="fa-regular fa-eye"></i>
            </button>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button class="primary-btn" type="submit">Login</button>

        <!-- Additional Links -->
        <div class="flex justify-between mt-6 text-sm">
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Create Account</a>
            <a href="{{ route('password.request') }}" class="text-gray-600 hover:underline">Forgot Password?</a>
        </div>
    </form>

    <!-- SweetAlert for Login Error Only -->
    @if ($errors->has('email') && $errors->first('email') === 'These credentials do not match our records.')
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed!',
                text: 'Wrong email or password. Please try again.',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

</x-guest-layout>
