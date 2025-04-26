<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->user_type !== 'admin') {
            if ($user->user_type === 'merchant') {
                return redirect()->route('merchant.dashboard')->with('error', 'You do not have permission to access admin pages.');
            }

            if ($user->user_type === 'user') {
                return redirect()->route('home')->with('error', 'You do not have permission to access admin pages.');
            }

            return redirect()->route('home')->with('error', 'You do not have permission.');
        }

        return $next($request);
    }
}
