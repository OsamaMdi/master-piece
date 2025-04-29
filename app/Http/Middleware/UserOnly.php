<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type === 'user') {
            return $next($request);
        }

        return redirect()->route('home'); 
    }
}
