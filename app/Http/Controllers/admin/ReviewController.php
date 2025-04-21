<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product.user']);

        // فلترة حسب التقييم
        if ($request->filter === 'worst') {
            $query->where('rating', '<=', 2);
        } elseif ($request->filter === 'best') {
            $query->where('rating', '>=', 4);
        }

        // ترتيب حسب السياق
        if (in_array($request->filter, ['worst', 'best'])) {
            // ترتيب حسب التقييم
            $query->orderBy('rating', $request->sort === 'oldest' ? 'asc' : 'desc');
        } else {
            // ترتيب حسب تاريخ الإنشاء
            $query->orderBy('created_at', $request->sort === 'oldest' ? 'asc' : 'desc');
        }

        $reviews = $query->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }



    public function create()
    {
        $users = User::all();
        $products = Product::all();
        return view('admin.reviews.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string',
        ]);

        Review::create($request->only('user_id', 'product_id', 'rating', 'review_text'));

        return redirect()->route('admin.reviews.index')->with('success', 'Review created successfully.');
    }

    public function show(Review $review)
    {
        $review->load(['user', 'product.user']);
    return view('admin.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $users = User::all();
        $products = Product::all();
        return view('admin.reviews.edit', compact('review', 'users', 'products'));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string',
        ]);

        $review->update($request->only('user_id', 'product_id', 'rating', 'review_text'));

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
