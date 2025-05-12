<?php

namespace App\Http\Controllers\User;






use App\Models\User;
use App\Models\Report;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WebsiteReview;
use App\Mail\BookingConfirmed;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;
use App\Traits\ReservationStatusTrait;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{

    use ReservationStatusTrait;
    //                   index page

    public function home()
    {
        $categories = Category::all();

        $reviews = WebsiteReview::with(['user' => function ($query) {
            $query->whereNotNull('profile_picture');
        }])
            ->whereHas('user', function ($query) {
                $query->whereNotNull('profile_picture');
            })
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('users.index', compact('categories', 'reviews'));
    }


    //                   show products by category

    public function showByCategory($id)
    {
        $category = Category::findOrFail($id);

        $allProducts = collect();

        // Get all user IDs with active subscriptions
        $activeSubscriptionUserIds = \App\Models\Subscription::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->pluck('user_id')
            ->toArray();

        // Get products from users with active subscriptions
        $activeProducts = Product::with(['images', 'reviews', 'favoredBy'])
            ->where('category_id', $id)
            ->where('status', '!=', 'blocked')
            ->whereIn('user_id', $activeSubscriptionUserIds)
            ->whereHas('user', function ($query) {
                $query->where('status', '!=', 'blocked');
            })
            ->withCount(['favoredBy as is_favorited' => function ($query) {
                $query->where('user_id', auth()->id())->whereNull('favorites.deleted_at');
            }])
            ->orderByDesc('created_at')
            ->get();

        // Get products from users without active subscriptions
        $inactiveProducts = Product::with(['images', 'reviews', 'favoredBy'])
            ->where('category_id', $id)
            ->where('status', '!=', 'blocked')
            ->whereNotIn('user_id', $activeSubscriptionUserIds)
            ->whereHas('user', function ($query) {
                $query->where('status', '!=', 'blocked');
            })
            ->withCount(['favoredBy as is_favorited' => function ($query) {
                $query->where('user_id', auth()->id())->whereNull('favorites.deleted_at');
            }])
            ->orderByDesc('created_at')
            ->get();

        // Merge both active and inactive product collections
        $allProducts = $activeProducts->concat($inactiveProducts);

        // Manual pagination
        $currentPage = request()->get('page', 1);
        $perPage = 15;
        $paginatedProducts = new LengthAwarePaginator(
            $allProducts->forPage($currentPage, $perPage),
            $allProducts->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $categories = Category::all();

        return view('users.products-by-category', [
            'category' => $category,
            'products' => $paginatedProducts,
            'categories' => $categories,
            'activeSubscriptionUserIds' => $activeSubscriptionUserIds,
        ]);
    }


    public function allTools()
    {
        $categories = Category::orderBy('name')->get();
        $allProducts = collect();

        $activeSubscriptionUserIds = \App\Models\Subscription::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->pluck('user_id')
            ->toArray();

        // Active subscription products
        $activeProducts = Product::with(['images', 'category', 'reviews', 'favoredBy'])
            ->where('status', '!=', 'blocked')
            ->whereIn('user_id', $activeSubscriptionUserIds)
            ->whereHas('user', function ($query) {
                $query->where('status', '!=', 'blocked');
            })
            ->withCount(['favoredBy as is_favorited' => function ($query) {
                $query->where('user_id', auth()->id())->whereNull('favorites.deleted_at');
            }])
            ->orderByDesc('created_at')
            ->get();

        // Inactive or no subscription products
        $inactiveProducts = Product::with(['images', 'category', 'reviews', 'favoredBy'])
            ->where('status', '!=', 'blocked')
            ->whereNotIn('user_id', $activeSubscriptionUserIds)
            ->whereHas('user', function ($query) {
                $query->where('status', '!=', 'blocked');
            })
            ->withCount(['favoredBy as is_favorited' => function ($query) {
                $query->where('user_id', auth()->id())->whereNull('favorites.deleted_at');
            }])
            ->orderByDesc('created_at')
            ->get();

        $allProducts = $activeProducts->concat($inactiveProducts);

        // Manual pagination
        $currentPage = request()->get('page', 1);
        $perPage = 15;
        $paginatedProducts = new LengthAwarePaginator(
            $allProducts->forPage($currentPage, $perPage),
            $allProducts->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('users.tools', [
            'products' => $paginatedProducts,
            'categories' => $categories,
            'activeSubscriptionUserIds' => $activeSubscriptionUserIds,
        ]);
    }


    //                   show tool Details
    public function showProduct($id)
    {
        $user = Auth::user();

        if ($user && $user->status === 'blocked') {
            Auth::logout();
            return redirect()->route('blocked.page');
        }

        $categories = Category::all();
        $product = Product::with(['user', 'category', 'images'])->findOrFail($id);
        $mainImage = $product->images->sortByDesc('created_at')->first()?->image_url;

        if ($mainImage) {
            $mainImage = asset('storage/' . $mainImage);
        } else {
            $mainImage = asset('img/logo.png');
        }

        $reviews = Review::with('user')
            ->where('product_id', $product->id)
            ->latest()
            ->paginate(5);

        $subscriptionActive = false;
        $owner = $product->user;

        if ($owner && $owner->subscription) {
            $today = now();
            $start = \Carbon\Carbon::parse($owner->subscription->start_date);
            $end = \Carbon\Carbon::parse($owner->subscription->end_date);

            $subscriptionActive = $today->between($start, $end);
        }

        return view('users.show-product', compact('product', 'mainImage', 'reviews', 'categories', 'subscriptionActive'));
    }


    //          store review

    public function storeReview(Request $request, $productId)
    {
        $user = auth()->user();

        $hasReservation = \App\Models\Reservation::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        if (!$hasReservation) {
            return redirect()->route('user.products.show', $productId)
                ->with('error', 'You must have a reservation for this product before submitting a review.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|max:1000',
        ]);

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        $product = Product::with('user')->findOrFail($productId);

        if ($product && $product->user) {
            NotificationService::send(
                $product->user->id,
                'You received a new review on your product "' . $product->name . '"',
                'product_review',
                url('/merchant/products/' . $product->id),
                'normal',
                $user->id
            );
        }

        return redirect()->route('user.products.show', $productId)->with('success', 'Review submitted successfully!');
    }




    //                   user feedback for this website
    public function userFeedback()
    {
        $allReviews = WebsiteReview::with('user')
            ->latest()
            ->take(18)
            ->get();

        $page = request()->get('page', 1);
        $perPage = 6;

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $allReviews->forPage($page, $perPage),
            $allReviews->count(),
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => request()->query()]
        );

        return view('users.about', ['recentReviews' => $paginated]);
    }


    //                  creat feedback for user
    public function storeWebsiteReview(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|max:1000',
        ]);

        $review = WebsiteReview::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        // Get all admin users
        $adminUsers = \App\Models\User::where('user_type', 'admin')->get();

        foreach ($adminUsers as $admin) {
            \App\Services\NotificationService::send(
                $admin->id,
                'A new website review was submitted by ' . auth()->user()->name,
                'website_review',
                route('admin.website-reviews.show', $review->id),
                'normal',
                auth()->id()
            );
        }

        return back()->with('success', 'Thank you for your feedback!');
    }




    public function indexActivity()
    {
        $userId = auth()->id();

        // Fetch user reservations ordered by status and start date
        $reservations = Reservation::with(['product', 'reports'])
            ->where('user_id', $userId)
            ->orderByRaw("
            CASE
                WHEN status = 'in_progress' THEN 1
                WHEN status = 'not_started' THEN 2
                WHEN status = 'completed' THEN 3
                WHEN status = 'cancelled' THEN 4
                ELSE 5
            END
        ")
            ->orderBy('start_date', 'asc')
            ->paginate(5);

        // Update reservation status if necessary
        foreach ($reservations as $reservation) {
            $this->checkAndUpdateStatus($reservation);
        }

        // Fetch user product reviews
        $productReviews = Review::with('product')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(5);

        // Fetch user website reviews
        $websiteReviews = WebsiteReview::where('user_id', $userId)
            ->latest()
            ->paginate(5);

        // Fetch all user-submitted reports
        $reports = \App\Models\Report::where('user_id', $userId)
            ->latest()
            ->paginate(5);

        // Fetch all user favorites with related product information (paginated)
        $favorites = \App\Models\Favorite::with(['product.images', 'product.category', 'product.user', 'product.reviews'])
            ->where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->paginate(5);

        return view('users.my-activity', compact(
            'reservations',
            'productReviews',
            'websiteReviews',
            'reports',
            'favorites'
        ));
    }


    // Handle cancelling a reservation
    public function cancelReservation($id)
    {
        $reservation = Reservation::with('product.user')->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'not_started')
            ->firstOrFail();

        $now = now();
        $startDate = Carbon::parse($reservation->start_date);
        $diffInHours = $now->diffInHours($startDate, false);

        if ($diffInHours <= 48) {
            if ($reservation->paid_amount == $reservation->total_price) {
                $reservation->paid_amount = round($reservation->total_price * 0.10, 2);
            }
        } else {
            $reservation->paid_amount = 0;
            $reservation->platform_fee = 0;
        }

        $reservation->status = 'cancelled';
        $reservation->save();

        // 📨 Send notification to the merchant
        if ($reservation->product && $reservation->product->user) {
            \App\Services\NotificationService::send(
                $reservation->product->user->id,
                'A reservation for your product "' . $reservation->product->name . '" was cancelled by the user ' . auth()->user()->name,
                'reservation_cancelled',
                url('/merchant/reservations/' . $reservation->id),
                'normal',
                auth()->id()
            );
        }

        return back()->with('success', 'Reservation cancelled successfully.');
    }


    // Handle reporting a reservation
    public function Report(Request $request)
    {
        $request->validate([
            'type' => 'required|in:reservation,product,general',
            'message' => 'required|string|min:10',
            'subject' => 'nullable|string|max:255',
            'target_id' => 'nullable|integer',
        ]);

        $reportableType = null;
        $reportableId = null;

        if ($request->type === 'reservation') {
            $reportableType = \App\Models\Reservation::class;
            $reportableId = $request->target_id;
        } elseif ($request->type === 'product') {
            $reportableType = \App\Models\Product::class;
            $reportableId = $request->target_id;
        } elseif ($request->type === 'general') {
            $reportableType = 'general';
            $reportableId = null;
        }

        $report = Report::create([
            'user_id'         => auth()->id(),
            'reportable_type' => $reportableType,
            'reportable_id'   => $reportableId,
            'target_type'     => $request->type,
            'subject'         => $request->subject,
            'message'         => $request->message,
            'status'          => 'pending',
        ]);

        // 🔔 Notify all admins
        $admins = User::where('user_type', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationService::send(
                $admin->id,
                'New report submitted by ' . auth()->user()->name . ' regarding ' . $request->type,
                'report',
                url('/admin/reports/' . $report->id),
                'important',
                auth()->id()
            );
        }

        return back()->with('success', 'Your report has been submitted successfully.');
    }


    public function toggle(Product $product)
    {
        $user = auth()->user();


        $favorite = Favorite::withTrashed()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            if ($favorite->trashed()) {

                $favorite->restore();

                return back()->with('success', 'Added to favorites successfully.');
            } else {

                $favorite->delete();

                return back()->with('warning', 'Removed from favorites.');
            }
        } else {

            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'slug' => Str::uuid(),
            ]);

            return back()->with('success', 'Added to favorites successfully.');
        }
    }
}

/*
<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use App\Models\Category;
use App\Models\WebsiteReview;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // ========== Home Page ==========
    public function home()
    {
        $categories = Category::all();

        $reviews = WebsiteReview::with(['user'])
            ->whereHas('user', function ($query) {
                $query->whereNotNull('profile_picture')
                      ->where('status', '!=', 'blocked');
            })
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('users.index', compact('categories', 'reviews'));
    }

    // ========== Show Products by Category ==========
    public function showByCategory($id)
    {
        $category = Category::findOrFail($id);

        $products = Product::with(['images', 'category', 'reviews', 'user'])
            ->where('category_id', $id)
            ->where('status', '!=', 'blocked')
            ->whereHas('user', function ($query) {
                $query->where('status', '!=', 'blocked');
            })
            ->latest()
            ->paginate(15);

        $categories = Category::all();

        return view('users.products-by-category', compact('category', 'products', 'categories'));
    }

    // ========== Show All Tools ==========
    public function allTools()
    {
        $categories = Category::orderBy('name')->get();

        $products = Product::with(['images', 'category', 'reviews', 'user'])
            ->where('status', '!=', 'blocked')
            ->whereHas('user', function ($query) {
                $query->where('status', '!=', 'blocked');
            })
            ->latest()
            ->paginate(15);

        return view('users.tools', compact('products', 'categories'));
    }
}
 */
