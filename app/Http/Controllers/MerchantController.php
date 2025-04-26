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
        $totalReservations = DB::table('reservations')->count();
        $totalProducts = DB::table('products')->count();
        $rentedTools = DB::table('reservations')
            ->where('status', 'approved')
            ->distinct('product_id')
            ->count('product_id');
        $cancelledReservations = DB::table('reservations')
            ->where('status', 'cancelled')
            ->count();
        $totalUsers = DB::table('users')->where('user_type', 'user')->count();
        $totalMerchants = DB::table('users')->where('user_type', 'merchant')->count();

        // ✨ New stats
        $totalRevenue = DB::table('reservations')->whereNotNull('total_price')->sum('total_price');
        $averageRating = DB::table('website_reviews')->avg('rating') ?? 0;


        // === مقارنة المستخدمين
        $usersThisWeek = DB::table('users')->where('created_at', '>=', now()->startOfWeek())->count();
        $usersLastWeek = DB::table('users')->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->startOfWeek()])->count();

        // === مقارنة المنتجات
        $productsThisWeek = DB::table('products')->where('created_at', '>=', now()->startOfWeek())->count();
        $productsLastWeek = DB::table('products')->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->startOfWeek()])->count();

        // === Daily Reservations
        $dailyReservationsRaw = DB::table('reservations')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $dailyReservations = [
            'labels' => $dailyReservationsRaw->pluck('date')->toArray(),
            'data' => $dailyReservationsRaw->pluck('count')->toArray()
        ];

        // === Reservations by Category
        $reservationsByCategoryRaw = DB::table('reservations')
            ->where('reservations.created_at', '>=', now()->startOfWeek())
            ->join('products', 'reservations.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as category', DB::raw('COUNT(reservations.id) as count'))
            ->groupBy('categories.name')
            ->orderByDesc('count')
            ->get();
        $reservationsByCategory = [
            'labels' => $reservationsByCategoryRaw->pluck('category')->toArray(),
            'data' => $reservationsByCategoryRaw->pluck('count')->toArray()
        ];

        // === Top Users
        $topUsers = DB::table('users')
        ->leftJoin('reservations', 'users.id', '=', 'reservations.user_id')
        ->where('users.user_type', 'user')
        ->select('users.id', 'users.name', 'users.email', DB::raw('COUNT(reservations.id) as reservations_count'))
        ->groupBy('users.id', 'users.name', 'users.email')
        ->orderByDesc('reservations_count')
        ->take(5)
        ->get();

        // === Top Rated Products
        $topRatedProducts = DB::table('products')
            ->join('reviews', 'products.id', '=', 'reviews.product_id')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->select(
                'products.id',
                'products.name',
                'products.user_id',
                'users.name as owner_name',
                DB::raw('AVG(reviews.rating) as average_rating')
            )
            ->groupBy('products.id', 'products.name', 'products.user_id', 'users.name')
            ->orderByDesc('average_rating')
            ->take(5)
            ->get();

        return view('admin.partials.dashboard', compact(
            'totalReservations',
            'totalProducts',
            'rentedTools',
            'cancelledReservations',
            'totalUsers',
            'totalMerchants',
            'totalRevenue',
            'averageRating',
            'usersThisWeek',
            'usersLastWeek',
            'productsThisWeek',
            'productsLastWeek',
            'dailyReservations',
            'reservationsByCategory',
            'topUsers',
            'topRatedProducts'
        ));
    }

}
