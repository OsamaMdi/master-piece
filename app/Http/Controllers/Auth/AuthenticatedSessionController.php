<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
       
        // 1. Validate the request
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Find the user
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // 3. Try to unblock if block expired
        if (
            $user->status === 'blocked' &&
            $user->blocked_until &&
            Carbon::now()->greaterThanOrEqualTo(Carbon::parse($user->blocked_until))
        ) {
            $user->status = 'active';
            $user->blocked_until = null;
            $user->block_reason = null;
            $user->save();
        }

        // 4. Check if still blocked after auto-unblock check
        if ($user->status === 'blocked') {
            session([
                'block_reason' => $user->block_reason,
                'blocked_until' => $user->blocked_until,
            ]);

            return redirect()->route('blocked.page');
        }

        // 5. Attempt login
        if (Auth::attempt(
            ['email' => $request->email, 'password' => $request->password],
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            $user = Auth::user();

            // 6. Redirect based on user type
            switch ($user->user_type) {
                case 'admin':
                    return redirect()->route('admin.dashboard');

                case 'merchant':
                    if ($user->status !== 'active') {
                        return redirect()->route('merchant.under_review');
                    }
                    return redirect()->route('merchant.products.index');

                default:
                    return redirect()->route('home');
            }
        }

        // 7. If login fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
