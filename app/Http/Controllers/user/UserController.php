<?php

namespace App\Http\Controllers\User;

use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\WebsiteReview;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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

    $products = Product::with(['images', 'reviews'])
        ->where('category_id', $id)
        ->latest()
        ->paginate(15);

    $categories = Category::all();

    return view('users.products-by-category', compact('category', 'products', 'categories'));
}



public function allTools()
{
    $categories = Category::orderBy('name')->get();
    $products = Product::with(['images', 'category', 'reviews'])
        ->latest()
        ->paginate(15);

    return view('users.tools', compact('products', 'categories'));
}

//                   show tool Details
public function showProduct($id)
{
    // Check if user is not logged in or is blocked
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    if ($user->status === 'blocked') {
        Auth::logout();
        return redirect()->route('blocked.page');
    }

    $categories = Category::all();
    $product = Product::with(['user', 'category', 'images'])->findOrFail($id);
    $mainImage = $product->images->sortByDesc('created_at')->first()?->image_url ?? 'images/default-product.png';
    $mainImage = asset('storage/' . $mainImage);

    $reviews = Review::with('user')
        ->where('product_id', $product->id)
        ->latest()
        ->paginate(5);

    return view('users.show-product', compact('product', 'mainImage', 'reviews', 'categories'));
}


//          store review

public function storeReview(Request $request, $productId)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'review_text' => 'required|string|max:1000',
    ]);

    Review::create([
        'user_id' => auth()->id(),
        'product_id' => $productId,
        'rating' => $request->rating,
        'review_text' => $request->review_text,
    ]);

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


public function storeWebsiteReview(Request $request)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'review_text' => 'required|string|max:1000',
    ]);

    WebsiteReview::create([
        'user_id' => auth()->id(),
        'rating' => $request->rating,
        'review_text' => $request->review_text,
    ]);

    return back()->with('success', 'Thank you for your feedback!');
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
