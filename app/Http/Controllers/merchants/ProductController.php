<?php

namespace App\Http\Controllers\merchants;

use App\Models\Image;
use App\Models\Product;
use App\Models\Category;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
    $products = Product::with(['user', 'category', 'images', 'reviews', 'reservations'])
        ->where('user_id', Auth::id())
        ->paginate(16);
    return view('merchants.product.products', compact('products', 'categories'));
    }



    public function store(Request $request)
    {
        try {
            // 1. Validate incoming data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|min:20',
                'price' => 'required|numeric|min:0.01',
                'quantity' => 'required|integer|min:1',
                'category_id' => 'required|exists:categories,id',
                'is_deliverable' => 'nullable|boolean', // ✅ checkbox
                'usage_notes' => 'required|string|min:5',
            ]);

            // 2. Create the product first WITHOUT slug
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => '', // placeholder slug
                'description' => $validated['description'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
                'status' => 'available',
                'user_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'is_deliverable' => $validated['is_deliverable'] ?? 0, // if not checked = 0
                'usage_notes' => $validated['usage_notes'],
            ]);

            // 3. Generate slug with ID after creation
            $slug = Str::slug($validated['name']) . '-' . $product->id;
            $product->update(['slug' => $slug]);

            // 4. Response based on request type
            if ($request->wantsJson()) {
                return response()->json(['newProductId' => $product->id]);
            }

            session(['newProductId' => $product->id]);
            return redirect()->route('merchant.products.index')
                             ->with('success', 'Product added successfully!')
                             ->with('showUploadModal', true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $e->errors()], 422);
            }

            Log::error('Validation Error: ' . $e->getMessage());
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            // Catch any other exceptions
            Log::error('Error while adding product: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while adding the product. Please try again.');
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

        return view('merchants.product.productShow', compact(
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
        return view('merchants.product.edit', compact('product'));
    }

   public function update(Request $request, string $id)
{
    try {
        $product = Product::findOrFail($id);

        // 1. Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'usage_notes' => 'required|string|min:5',
            'is_deliverable' => 'nullable|boolean',
        ]);

        // 2. Prepare the final validated data
        $validated['is_deliverable'] = $request->has('is_deliverable') ? 1 : 0;

        // 3. Update the product
        $product->update($validated);

        // 4. Return appropriate response
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product
            ]);
        }

        return redirect()->route('merchant.products.index')->with('success', 'Product updated successfully!');

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
}




    public function destroy(string $id)
    {
        try {
            $product = Product::findOrFail($id);

            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $image->delete();
            }

            $product->delete();

            return redirect()->route('merchant.products.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error while deleting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the product.');
        }
    }

    public function updateImages(Request $request, string $id)
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

            return redirect()->route('merchant.products.show', $product->id)
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
    }

    public function disableProduct(Request $request, Product $product)
{
    $request->validate([
        'from_date' => 'required|date|after_or_equal:today',
        'to_date' => 'required|date|after:from_date',
        'reason' => 'required|string|min:5',
    ]);

    // Update product status to maintenance
    $product->update([
        'status' => 'maintenance',
    ]);

    // Cancel all reservations within the date range
    Reservation::where('product_id', $product->id)
        ->whereDate('start_date', '>=', $request->from_date)
        ->whereDate('end_date', '<=', $request->to_date)
        ->update([
            'status' => 'cancelled',
        ]);

    return response()->json(['message' => 'Product disabled and reservations cancelled successfully.']);
}


public function toggleStatus(Product $product)
{
    if ($product->status === 'maintenance') {
        $product->update(['status' => 'available']);
    }

    return redirect()->back()->with('success', 'Product status updated successfully!');
}

}
