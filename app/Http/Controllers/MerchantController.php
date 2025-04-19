<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MerchantController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $totalProducts = $user->products()->count();

        $totalReservations = DB::table('reservations')
                            ->join('products', 'reservations.product_id', '=', 'products.id')
                            ->where('products.user_id', $user->id)
                            ->count();

        $rentedTools = DB::table('reservations')
                        ->join('products', 'reservations.product_id', '=', 'products.id')
                        ->where('products.user_id', $user->id)
                        ->distinct('reservations.product_id')
                        ->count('reservations.product_id');

        return view('merchants.partials.dashboard', compact('totalProducts', 'totalReservations', 'rentedTools'));
    }
    public function adminDashboard()
    {
        $user = Auth::user();

        $totalProducts = $user->products()->count();

        $totalReservations = DB::table('reservations')
                            ->join('products', 'reservations.product_id', '=', 'products.id')
                            ->where('products.user_id', $user->id)
                            ->count();

        $rentedTools = DB::table('reservations')
                        ->join('products', 'reservations.product_id', '=', 'products.id')
                        ->where('products.user_id', $user->id)
                        ->distinct('reservations.product_id')
                        ->count('reservations.product_id');

        return view('Admin.partials.dashboard', compact('totalProducts', 'totalReservations', 'rentedTools'));
    }
}
