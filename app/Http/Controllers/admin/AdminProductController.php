<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\User;

use App\Models\Image;
use App\Models\Product;
use App\Models\Category;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;
use App\Mail\BlockProductNotification;
use App\Mail\DeleteProductNotification;
use Illuminate\Support\Facades\Storage;





class AdminProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $products = Product::with(['user', 'category', 'images', 'reviews', 'reservations'])
            ->latest()
            ->paginate(16);

        $merchants = User::where('user_type', 'merchant')->get();

        return view('admin.product.products', compact('products', 'categories', 'merchants'));
    }



    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'price' => 'required|numeric|min:0.01',
                'quantity' => 'required|integer|min:1',
                'category_id' => 'required|exists:categories,id',
                'merchant_id' => 'required|exists:users,id',
                'usage_notes' => 'nullable|string|max:1000',
            ]);

            // Extra check just in case
            if (!$request->has('merchant_id') || empty($request->merchant_id)) {
                return redirect()->back()->with('error', 'Merchant is required to assign the product.')->withInput();
            }

            // Generate slug from name
            $slug = Str::slug($request->name);

            // Create product
            $product = Product::create([
                'name' => $request->name,
                'slug' => $slug,  
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'status' => 'available',
                'user_id' => $request->merchant_id,
                'category_id' => $request->category_id,
                'usage_notes' => $request->usage_notes,
            ]);

            if ($request->wantsJson()) {
                return response()->json(['newProductId' => $product->id]);
            }

            session(['newProductId' => $product->id]);
            return redirect()->route('admin.products.index')
                             ->with('success', 'Product added successfully!')
                             ->with('showUploadModal', true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $e->errors()], 422);
            }

            Log::error('Validation Error: ' . $e->getMessage());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unexpected error occurred.')->withInput();
        }
    }



    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $path = $request->file('image')->store('products', 'public');

            Image::create([
                'product_id' => $request->product_id,
                'image_url' => $path,
            ]);

            return back()->with('success', 'Image uploaded successfully!')->with('showUploadModal', true);
        } catch (\Exception $e) {
            Log::error('Error while uploading image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while uploading the image.');
        }
    }

    public function show(string $id)
    {
        $product = Product::with([
            'user',
            'category',
            'images',
            'reservations.user',
        ])->findOrFail($id);

        $reviews = $product->reviews()->with('user')->latest()->paginate(10);

        $reservationsCount = $product->reservations->count();
        $completedReservationsCount = $product->reservations
            ->where('end_date', '<', now())
            ->where('status', 'approved')
            ->count();
        $upcomingReservationsCount = $product->reservations
            ->where('start_date', '>', now())
            ->where('status', 'approved')
            ->count();

        $categories = Category::all();

        return view('admin.product.productShow', compact(
            'product',
            'reviews',
            'reservationsCount',
            'completedReservationsCount',
            'upcomingReservationsCount',
            'categories'
        ));
    }



    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.product.edit', compact('product'));
    }

   /*  public function update(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'price' => 'required|numeric|min:0.01',
                'quantity' => 'required|integer|min:1',
                'status' => 'required|in:available,reserved,unavailable',
                'category_id' => 'required|exists:categories,id',
            ]);

            $product->update($validated);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Product updated successfully!',
                    'product' => $product
                ]);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'errors' => $e->errors(),
                    'message' => 'Validation Error!'
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'errors' => ['server' => 'Unexpected error occurred'],
                    'message' => 'Unexpected Error!'
                ], 500);
            }
            return redirect()->back()->with('error', 'Unexpected error occurred.');
        }
    } */



    public function destroy(string $id)
    {
        try {
            $product = Product::with('user', 'images')->findOrFail($id);

            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $image->delete();
            }

            // 📨 إرسال إيميل للمستخدم
            Mail::to($product->user->email)->send(new DeleteProductNotification($product));

            // 🛎️ إرسال إشعار لصاحب المنتج
            NotificationService::send(
                $product->user->id,
                'Your product "' . $product->name . '" was deleted by an admin.',
                'product_deleted',
                null,
                'important',
                auth()->id()
            );

            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error while deleting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the product.');
        }
    }


  /*   public function updateImages(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);

            $request->validate([
                'replace_images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'new_images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // === Replace existing images ===
            if ($request->hasFile('replace_images')) {
                foreach ($request->file('replace_images') as $imageId => $newImageFile) {
                    if ($newImageFile) {
                        $oldImage = \App\Models\Image::find($imageId);

                        if ($oldImage) {
                            if (Storage::disk('public')->exists($oldImage->image_url)) {
                                Storage::disk('public')->delete($oldImage->image_url);
                            }
                            $oldImage->delete();
                        }

                        $path = $newImageFile->store('products', 'public');

                        $product->images()->create([
                            'image_url' => $path,
                        ]);
                    }
                }
            }

            // === Add new images ===
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $newImage) {
                    $path = $newImage->store('products', 'public');

                    $product->images()->create([
                        'image_url' => $path,
                    ]);
                }
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Images updated successfully!']);
            }

            return redirect()->route('admin.products.show', $product->id)
                             ->with('success', 'Images updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $e->errors(), 'message' => 'Validation error.'], 422);
            }
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error updating images: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['message' => 'An unexpected error occurred.'], 500);
            }

            return redirect()->back()->with('error', 'Error updating images.');
        }
    } */

   /*  public function blockWithCancel($id)
    {
        $product = Product::with('reservations')->findOrFail($id);
        $product->status = 'blocked';
        $product->save();

        $today = now()->startOfDay();

        $product->reservations()
            ->whereDate('start_date', '>', $today)
            ->whereIn('status', ['pending', 'approved'])
            ->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Product blocked and all future reservations cancelled.');
    } */

              // Block a product with a reason and duration


              public function block(Request $request, Product $product)
{
    $request->validate([
        'duration' => 'required',
        'reason' => 'required|string|max:255',
    ]);


    if ($request->duration == 'permanent') {
        $blockedUntil = null;
        $durationText = 'Permanent';
    } else {
        $blockedUntil = now()->addDays((int) $request->duration);
        $durationText = ((int) $request->duration === 1 ? '1 day' : ((int) $request->duration === 7 ? '1 week' : $request->duration . ' days'));
    }


    $product->update([
        'status' => 'blocked',
        'block_reason' => $request->reason,
        'blocked_until' => $blockedUntil,
    ]);

    Mail::to($product->user->email)->send(new BlockProductNotification(
        $product,
        $request->reason,
        $durationText
    ));


    NotificationService::send(
        $product->user->id,
        'Your product "' . $product->name . '" was blocked by an admin for: ' . $durationText . '. Reason: ' . $request->reason,
        'product_blocked',
        url('/merchant/products/' . $product->id),
        'important',
        auth()->id()
    );

    return redirect()->back()->with('success', 'Product blocked successfully.');
}


    // Unblock a product

public function unblock(Product $product)
{
    $product->update([
        'status' => 'available',
        'block_reason' => null,
        'blocked_until' => null,
    ]);

    return redirect()->back()->with('success', 'Product unblocked successfully.');
}

}
