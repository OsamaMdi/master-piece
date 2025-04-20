<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteReview;
use App\Models\User;
use Illuminate\Http\Request;

class WebsiteReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = WebsiteReview::with('user');

        if ($request->has('filter') && $request->filter === 'worst') {
            $query->where('rating', '<=', 2);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.website_reviews.index', compact('reviews'));
    }


    public function create()
    {
        $users = User::all();
        return view('admin.website_reviews.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string',
        ]);

        WebsiteReview::create($request->only('user_id', 'rating', 'review_text'));

        return redirect()->route('admin.website-reviews.index')->with('success', 'Website review created successfully.');
    }

    public function show(WebsiteReview $websiteReview)
    {
        $websiteReview->load('user');
    return view('admin.website_reviews.show', ['review' => $websiteReview]);
    }

    public function edit(WebsiteReview $websiteReview)
    {
        $users = User::all();
        return view('admin.website_reviews.edit', ['review' => $websiteReview, 'users' => $users]);
    }

    public function update(Request $request, WebsiteReview $websiteReview)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string',
        ]);

        $websiteReview->update($request->only('user_id', 'rating', 'review_text'));

        return redirect()->route('admin.website-reviews.index')->with('success', 'Website review updated successfully.');
    }

    public function destroy(WebsiteReview $websiteReview)
    {
        $websiteReview->delete();
        return redirect()->route('admin.website-reviews.index')->with('success', 'Website review deleted.');
    }
}
