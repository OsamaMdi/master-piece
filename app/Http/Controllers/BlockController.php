<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlockController extends Controller
{


public function show()
{
    $user = Auth::user();
    $blockedUntil = $user->blocked_until;

    $start = now();
    $end = $blockedUntil ?? now()->addYears(20); // مؤقت للحظر الدائم

    $reservations = collect();

    if ($user->user_type === 'user') {
        $reservations = $user->reservations()
            ->where('status', 'not_started')
            ->whereDate('start_date', '>=', $start)
            ->whereDate('start_date', '<=', $end)
            ->with('product')
            ->get();
    } elseif ($user->user_type === 'merchant') {
        $reservations = Reservation::whereHas('product', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('status', 'not_started')
        ->whereDate('start_date', '>=', $start)
        ->whereDate('start_date', '<=', $end)
        ->with(['product', 'user'])
        ->get();
    }

    return view('users.blocked', [
        'reservations' => $reservations,
        'blocked_until' => $blockedUntil,
        'block_reason' => $user->block_reason,
    ]);
}

}
