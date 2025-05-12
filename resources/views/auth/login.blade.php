<x-guest-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" id="login-form" class="auth-card relative">
        @csrf

        <!-- Email -->
        <div class="input-group">
            <label class="input-label" for="email">Email</label>
            <input id="email" class="input-field" type="email" name="email" required autofocus autocomplete="username" />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password Field (Login) -->
        <div class="input-group">
            <label class="input-label" for="login_password">Password</label>

            <div class="relative">
<input id="login_password" type="password" name="password" class="input-field input-with-icon" required autocomplete="current-password" />
                <button type="button" id="toggleLoginPassword" class="password-toggle-btn" aria-label="Show password">
                    <i id="toggleLoginPasswordIcon" class="fa-regular fa-eye"></i>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
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
