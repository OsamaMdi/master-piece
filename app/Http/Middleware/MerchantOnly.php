<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MerchantOnly
{
    public function handle($request, Closure $next)
    {
        // ✅ أول شيء: إذا رايح على under-review خلّيه يدخل بدون مشاكل
        if ($request->routeIs('merchant.under_review')) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access merchant pages.');
        }

        $user = Auth::user();

        if ($user->status === 'blocked') {
            Auth::logout();
            return redirect()->route('blocked.page');
        }

        if ($user->user_type !== 'merchant') {
            if ($user->user_type === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to access merchant pages.');
            }

            if ($user->user_type === 'user') {
                return redirect()->route('home')->with('error', 'You do not have permission to access merchant pages.');
            }

            return redirect()->route('home')->with('error', 'You do not have permission.');
        }

        // ✅ إذا التاجر مش active، رجعه إلى صفحة under-review
        if ($user->status !== 'active') {
            return redirect()->route('merchant.under_review');
        }

        return $next($request);
    }
}
