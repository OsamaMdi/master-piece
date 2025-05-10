<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MerchantController extends Controller
{


    public function dashboard()
{
    $user = Auth::user();
    $now = Carbon::now();

    $startOfMonth = $now->copy()->startOfMonth();
    $endOfMonth = $now->copy()->endOfMonth();
    $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
    $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

    $totalActiveProducts = $user->products()
        ->where('status', 'available')
        ->whereNull('deleted_at')
        ->count();

    $cancelledReservations = DB::table('reservations')
        ->join('products', 'reservations.product_id', '=', 'products.id')
        ->where('products.user_id', $user->id)
        ->where('reservations.status', 'cancelled')
        ->whereBetween('reservations.created_at', [$startOfMonth, $endOfMonth])
        ->where('products.status', 'available')
        ->whereNull('products.deleted_at')
        ->whereNull('reservations.deleted_at')
        ->count();

    $averageRating = DB::table('reviews')
        ->join('products', 'reviews.product_id', '=', 'products.id')
        ->where('products.user_id', $user->id)
        ->whereNull('reviews.deleted_at')
        ->whereNull('products.deleted_at')
        ->avg('reviews.rating');

    $totalPaid = DB::table('reservations')
        ->join('products', 'reservations.product_id', '=', 'products.id')
        ->where('products.user_id', $user->id)
        ->where('reservations.status', 'completed')
        ->whereBetween('reservations.created_at', [$startOfMonth, $endOfMonth])
        ->whereNull('products.deleted_at')
        ->whereNull('reservations.deleted_at')
        ->sum('reservations.paid_amount');

    $completedReservations = DB::table('reservations')
        ->join('products', 'reservations.product_id', '=', 'products.id')
        ->where('products.user_id', $user->id)
        ->where('reservations.status', 'completed')
        ->whereBetween('reservations.created_at', [$startOfMonth, $endOfMonth])
        ->whereNull('products.deleted_at')
        ->whereNull('reservations.deleted_at')
        ->count();

    $platformCommission = DB::table('reservations')
        ->join('products', 'reservations.product_id', '=', 'products.id')
        ->where('products.user_id', $user->id)
        ->where('reservations.status', 'completed')
        ->whereBetween('reservations.created_at', [$startOfMonth, $endOfMonth])
        ->whereNull('products.deleted_at')
        ->whereNull('reservations.deleted_at')
        ->sum('reservations.platform_fee');

    $blockedProducts = DB::table('products')
        ->where('user_id', $user->id)
        ->whereNull('deleted_at')
        ->where(function ($query) use ($now) {
            $query->where('status', 'blocked')
                  ->orWhere('blocked_until', '>', $now);
        })->count();

    $productReports = DB::table('reports')
        ->join('products', 'reports.reportable_id', '=', 'products.id')
        ->where('reports.target_type', 'product')
        ->where('products.user_id', $user->id)
        ->whereNull('products.deleted_at')
        ->whereNull('reports.deleted_at')
        ->count();

    $completedThisMonth = DB::table('reservations')
        ->join('products', 'reservations.product_id', '=', 'products.id')
        ->where('products.user_id', $user->id)
        ->where('reservations.status', 'completed')
        ->whereBetween('reservations.start_date', [$startOfMonth, $endOfMonth])
        ->whereNull('products.deleted_at')
        ->whereNull('reservations.deleted_at')
        ->count();

    $completedLastMonth = DB::table('reservations')
        ->join('products', 'reservations.product_id', '=', 'products.id')
        ->where('products.user_id', $user->id)
        ->where('reservations.status', 'completed')
        ->whereBetween('reservations.start_date', [$startOfLastMonth, $endOfLastMonth])
        ->whereNull('products.deleted_at')
        ->whereNull('reservations.deleted_at')
        ->count();

    $topProductsCombined = DB::table('products')
        ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
        ->leftJoin('reservations', function ($join) {
            $join->on('products.id', '=', 'reservations.product_id')
                 ->where('reservations.status', '=', 'completed')
                 ->whereNull('reservations.deleted_at');
        })
        ->where('products.user_id', $user->id)
        ->whereNull('products.deleted_at')
        ->where(function ($query) {
            $query->whereNull('reviews.deleted_at')->orWhereNull('reviews.id'); // لأن leftJoin
        })
        ->groupBy('products.id', 'products.name')
        ->select(
            'products.id',
            'products.name',
            DB::raw('COALESCE(AVG(reviews.rating), 0) as avg_rating'),
            DB::raw('COUNT(DISTINCT reservations.id) as completed_count')
        )
        ->orderByDesc('completed_count')
        ->limit(5)
        ->get();

    return view('merchants.partials.dashboard', compact(
        'totalActiveProducts',
        'cancelledReservations',
        'averageRating',
        'totalPaid',
        'completedReservations',
        'platformCommission',
        'blockedProducts',
        'productReports',
        'completedThisMonth',
        'completedLastMonth',
        'topProductsCombined'
    ));
}



    public function adminDashboard()
{
    $totalReservations = DB::table('reservations')->count();
    $totalProducts = DB::table('products')->count();
    $totalUsers = DB::table('users')->where('user_type', 'user')->count();
    $totalMerchants = DB::table('users')->where('user_type', 'merchant')->count();

    // ⬇️ الشهر الحالي
    $startOfCurrentMonth = now()->startOfMonth();
    $endOfCurrentMonth = now()->endOfMonth();

    $currentMonthProfit = DB::table('reservations')
        ->where('status', 'completed')
        ->whereBetween('start_date', [$startOfCurrentMonth, $endOfCurrentMonth])
        ->sum('platform_fee');

    $totalRevenue = DB::table('reservations')
        ->whereNotNull('total_price')
        ->whereBetween('start_date', [$startOfCurrentMonth, $endOfCurrentMonth])
        ->sum('total_price');

    $currentMonthName = now()->format('F Y');

    // ⬇️ تقييم الموقع (نجوم)
    $rawAverage = DB::table('website_reviews')->avg('rating') ?? 0;
    $averageRating = round($rawAverage * 2) / 2;

    // ⬇️ بلاغات قيد الانتظار
    $pendingReports = DB::table('reports')
        ->where('status', 'pending')
        ->count();

    // ⬇️ إحصائيات المستخدمين والمنتجات بالأسبوع
    $usersThisWeek = DB::table('users')
        ->where('created_at', '>=', now()->startOfWeek())
        ->count();

    $usersLastWeek = DB::table('users')
        ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->startOfWeek()])
        ->count();

    $productsThisWeek = DB::table('products')
        ->where('created_at', '>=', now()->startOfWeek())
        ->count();

    $productsLastWeek = DB::table('products')
        ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->startOfWeek()])
        ->count();

    // ⬇️ مقارنة حجوزات الشهر الحالي والماضي
    $startOfLastMonth = now()->subMonth()->startOfMonth();
    $endOfLastMonth = now()->subMonth()->endOfMonth();

    $currentMonthData = DB::table('reservations')
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->keyBy('date');

    $lastMonthData = DB::table('reservations')
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->keyBy('date');

    $daysInCurrentMonth = now()->daysInMonth;
    $labels = [];
    $currentMonthCounts = [];
    $lastMonthCounts = [];

    for ($day = 1; $day <= $daysInCurrentMonth; $day++) {
        $dayFormattedCurrent = now()->copy()->day($day)->format('Y-m-d');
        $dayFormattedLast = now()->subMonth()->copy()->day($day)->format('Y-m-d');

        $labels[] = $day;
        $currentMonthCounts[] = $currentMonthData[$dayFormattedCurrent]->count ?? 0;
        $lastMonthCounts[] = $lastMonthData[$dayFormattedLast]->count ?? 0;
    }

    $monthlyReservationComparison = [
        'labels' => $labels,
        'currentMonth' => $currentMonthCounts,
        'lastMonth' => $lastMonthCounts
    ];

    // ⬇️ الحجوزات حسب التصنيف لأسبوع سابق
    $startOfLastWeek = now()->subWeek()->startOfWeek();
    $endOfLastWeek = now()->subWeek()->endOfWeek();

    $reservationsByCategoryRaw = DB::table('reservations')
        ->where('reservations.status', 'completed')
        ->whereBetween('reservations.start_date', [$startOfLastWeek, $endOfLastWeek])
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

    // ⬇️ أفضل المستخدمين
    $topUsers = DB::table('users')
        ->leftJoin('reservations', 'users.id', '=', 'reservations.user_id')
        ->where('users.user_type', 'user')
        ->select('users.id', 'users.name', 'users.email', DB::raw('COUNT(reservations.id) as reservations_count'))
        ->groupBy('users.id', 'users.name', 'users.email')
        ->orderByDesc('reservations_count')
        ->take(5)
        ->get();

    // ⬇️ أفضل المنتجات تقييماً
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
        'totalUsers',
        'totalMerchants',
        'totalRevenue',
        'averageRating',
        'usersThisWeek',
        'usersLastWeek',
        'productsThisWeek',
        'productsLastWeek',
        'reservationsByCategory',
        'topUsers',
        'topRatedProducts',
        'currentMonthProfit',
        'currentMonthName',
        'pendingReports',
        'monthlyReservationComparison'
    ));
}



}
