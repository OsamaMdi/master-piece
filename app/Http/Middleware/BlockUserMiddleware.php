<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BlockUserMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->status === 'blocked') {

               
                if ($request->is('admin/*') || $request->is('merchant/*') || $request->is('dashboard') || $request->is('profile') || $request->is('settings')) {
                    Auth::logout();
                    return redirect()->route('blocked.page');
                }
            }
        }

        return $next($request);
    }
}
