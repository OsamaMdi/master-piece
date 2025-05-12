<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Auth\Events\PasswordReset;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */


public function store(Request $request): RedirectResponse
{
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => [
            'required',
            'confirmed',
            PasswordRule::min(8)
                ->mixedCase()
                ->numbers(),
        ],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) use ($request) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Password reset successfully. You can now log in.')
        : back()->withInput($request->only('email'))
                ->withErrors(['error' => 'Failed to reset password. The token may be invalid or expired.']);
}

}
