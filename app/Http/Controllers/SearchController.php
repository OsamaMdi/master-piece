<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Reservation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function liveSearch(Request $request)
{
    $query = $request->input('query');

    // Fetch products with their images
    $products = Product::where('status', '!=', 'blocked')
        ->where('name', 'like', "%{$query}%")
        ->with('images') // Load the related images
        ->take(5)
        ->get()
        ->map(function ($product) {
            // Get the latest image from the relationship
            $image = $product->images->sortByDesc('created_at')->first();
            $imagePath = $image
                ? asset('storage/' . $image->image_url)
                : asset('img/logof.png'); // Fallback image if no image found

            return [
                'type' => 'product',
                'id' => $product->id,
                'name' => $product->name,
                'image' => $imagePath,
                'url' => route('user.products.show', ['id' => $product->id]),
            ];
        });

    // Fetch matching categories
    $categories = Category::where('name', 'like', "%{$query}%")
        ->take(5)
        ->get()
        ->map(function ($category) {
            $url = route('products.by.category', ['id' => $category->id]);

            return [
                'type' => 'category',
                'id' => $category->id,
                'name' => $category->name,
                'image' => asset('img/logof.png'),
                'url' => $url,
            ];
        });

    // Combine both and return as JSON
    return response()->json(array_merge($products->toArray(), $categories->toArray()));
}




//                   admin search

public function adminSearch(Request $request)
{
    $query = $request->input('query');

    if (!$query || trim($query) === '') {
        return response()->json([]);
    }

    // 🔎 المنتجات (Products)
    $products = Product::with('images')
        ->whereNull('deleted_at')
        ->where('name', 'like', "%{$query}%")
        ->get()
        ->map(function ($product) {
            $image = $product->images->sortByDesc('created_at')->first();
            return [
                'type' => 'product',
                'name' => $product->name,
                'status' => $product->status,
                'image' => $image ? asset('storage/' . $image->image_url) : asset('/img/logof.png'),
                'url' => route('admin.products.show', $product->id),
            ];
        });

    // 🔎 المستخدمين (Users)
    $users = User::whereNull('deleted_at')
    ->where(function ($q) use ($query) {
        $q->where('name', 'like', "%{$query}%")
          ->orWhere('user_type', 'like', "%{$query}%")
          ->orWhere('status', 'like', "%{$query}%");
    })
    ->get()
    ->map(function ($user) {
        return [
            'type' => 'user',
            'name' => $user->name,
            'user_type' => $user->user_type,
            'status' => $user->status,
            'role' => $user->role ,
            'image' => $user->profile_picture
                ? asset('storage/' . $user->profile_picture)
                : asset('/img/logof.png'),
            'url' => route('admin.users.show', $user->id),
        ];
    });

    // 🔎 الحجوزات (Reservations)
    $reservations = Reservation::with('user')
        ->whereNull('deleted_at')
        ->where(function ($q) use ($query) {
            $q->where('status', 'like', "%{$query}%")
              ->orWhereHas('user', function ($q2) use ($query) {
                  $q2->where('name', 'like', "%{$query}%");
              });
        })
        ->get()
        ->map(function ($res) {
            return [
                'type' => 'reservation',
                'user' => $res->user?->name ?? 'Unknown',
                'status' => $res->status,
                'url' => route('admin.reservation.details', $res->id),
            ];
        });

    return response()->json(
        array_merge($products->toArray(), $users->toArray(), $reservations->toArray())
    );
}


public function merchantSearch(Request $request)
{
    $query = $request->input('query');

    // جلب المنتجات حسب الاسم أو اسم الكاتيجوري
    $products = Product::with('images', 'category')
        ->where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhereHas('category', function ($q2) use ($query) {
                  $q2->where('name', 'like', "%{$query}%");
              });
        })
        ->where('user_id', auth()->id()) // فقط منتجات التاجر الحالي
        ->take(10)
        ->get()
        ->map(function ($product) {
            $image = $product->images->sortByDesc('created_at')->first();
            return [
                'type' => 'product',
                'id' => $product->id,
                'name' => $product->name,
                'status' => $product->status,
                'image' => $image ? asset('storage/' . $image->image_url) : asset('img/logof.png'),
                'url' => route('merchant.products.show', $product->id),
            ];
        });

    // جلب الحجوزات حسب حالة الحجز أو اسم البرودكت
    $reservations = Reservation::with('product')
        ->whereHas('product', function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->where('user_id', auth()->id()); // حجوزات على منتجات التاجر فقط
        })
        ->orWhere(function ($q) use ($query) {
            $q->where('status', 'like', "%{$query}%")
              ->whereHas('product', function ($q2) {
                  $q2->where('user_id', auth()->id());
              });
        })
        ->take(10)
        ->get()
        ->map(function ($reservation) {
            return [
                'type' => 'reservation',
                'id' => $reservation->id,
                'name' => $reservation->product->name,
                'status' => $reservation->status,
                'user' => $reservation->user->name ?? 'N/A',
                'image' => $reservation->product->images->first()
                    ? asset('storage/' . $reservation->product->images->first()->image_url)
                    : asset('img/logof.png'),
                'url' => route('merchant.reservation.details', $reservation->id),
            ];
        });

    return response()->json(array_merge($products->toArray(), $reservations->toArray()));
}

}
