<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AutoUnblockUser
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // فك البلوك لو الوقت خلص
            if (
                $user->status === 'blocked' &&
                $user->blocked_until &&
                Carbon::now()->greaterThanOrEqualTo(Carbon::parse($user->blocked_until))
            ) {
                $user = $user->fresh();
                $user->status = 'active';
                $user->blocked_until = null;
                $user->block_reason = null;
                $user->save();

            }


            if ($user->status === 'blocked') {
                Auth::logout();
                session([
                    'block_reason' => $user->block_reason,
                    'blocked_until' => $user->blocked_until,
                    'blocked_message' => 'Your account has been blocked. Please check the reason below.',
                ]);

                return redirect()->route('blocked.page');
            }
        }

        return $next($request);
    }
}

