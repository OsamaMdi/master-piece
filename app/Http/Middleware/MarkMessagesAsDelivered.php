<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class MarkMessagesAsDelivered
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            Message::where('delivered', false)
                ->where(function ($query) use ($user) {
                    $query->where('receiver_id', $user->id)
                          ->where('receiver_type', get_class($user));
                })
                ->update(['delivered' => true]);
        }

        return $next($request);
    }
}
