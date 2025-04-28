<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class AutoUnblockProduct
{
    public function handle(Request $request, Closure $next)
    {
        Product::where('status', 'blocked')
            ->whereNotNull('blocked_until')
            ->where('blocked_until', '<=', Carbon::now())
            ->update([
                'status' => 'available',
                'block_reason' => null,
                'blocked_until' => null,
            ]);

        return $next($request);
    }
}

