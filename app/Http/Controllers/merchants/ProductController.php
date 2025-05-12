<?php

namespace App\Http\Controllers\merchants;

use App\Models\User;
use App\Models\Image;
use App\Models\Product;
use App\Models\Category;
use App\Models\Reservation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\BookingConfirmed;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ReservationActionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;
use App\Mail\SuggestDelayWithApproval;
use App\Traits\ReservationStatusTrait;
use Illuminate\Support\Facades\Storage;
use App\Mail\ReservationCancelledWithSuggestions;


class ProductController extends Controller
{
    use ReservationStatusTrait;

    public function index()
    {
        $categories = Category::all();
        $products = Product::with(['user', 'category', 'images', 'reviews', 'reservations'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(16);

        return view('merchants.product.products', compact('products', 'categories'));
    }



    public function create()
{
    $categories = Category::all();
    return view('merchants.product.create', compact('categories'));
}

public function store(Request $request)
{
    try {
        // ✅ 1. Validate all inputs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            /* 'is_deliverable' => 'nullable|boolean', */
            'usage_notes' => 'required|string|min:5',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // ✅ 2. Check if merchant exceeded product limit
        $user = Auth::user();
        $currentProductCount = Product::where('user_id', $user->id)->count(); // soft deletes are ignored by default

        if ($currentProductCount >= $user->product_limit) {
            return back()->with('error', 'You have reached your product limit. Please remove an existing product before adding a new one.');
        }

        // ✅ 3. Create the product
        $product = Product::create([
            'name' => $validated['name'],
            'slug' => '',
            'description' => $validated['description'],
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'status' => 'available',
            'user_id' => $user->id,
            'category_id' => $validated['category_id'],
            /* 'is_deliverable' => $validated['is_deliverable'] ?? 0, */
            'usage_notes' => $validated['usage_notes'],
        ]);

        // ✅ 4. Generate slug
        $slug = Str::slug($validated['name']) . '-' . $product->id;
        $product->update(['slug' => $slug]);

        // ✅ 5. Save images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                Image::create([
                    'product_id' => $product->id,
                    'image_url' => $path,
                ]);
            }
        }

        // ✅ 6. Notify all admins
        $category = Category::find($validated['category_id']);
        $admins = User::where('user_type', 'admin')->get();

        foreach ($admins as $admin) {
            NotificationService::send(
                $admin->id,
                'A new product "' . $product->name . '" was added by ' . $user->name . ' in category "' . $category->name . '".',
                'new_product',
                url('/admin/products/' . $product->id),
                'normal',
                $user->id
            );
        }

        // ✅ 7. Redirect with success
        return redirect()->route('merchant.products.index')
                         ->with('success', 'Product and images added successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('Error while adding product: ' . $e->getMessage());
        return back()->with('error', 'Something went wrong while adding the product.');
    }
}




    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                Image::create([
                    'product_id' => $request->product_id,
                    'image_url' => $path,
                ]);
            }

            return back()->with('success', 'Images uploaded successfully!')->with('showUploadModal', true);

        } catch (\Exception $e) {
            Log::error('Error while uploading images: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while uploading the images.');
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
        $categories = Category::all();

        return view('merchants.product.edit', compact('product', 'categories'));
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

        /* // 2. Prepare the final validated data
        $validated['is_deliverable'] = $request->has('is_deliverable') ? 1 : 0; */

        // 3. Update the product
        $product->update($validated);

        // 4. Return appropriate response
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product
            ]);
        }

        return redirect()->route('merchant.products.edit', $product->id)->with('success', 'Product updated successfully!');

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

public function updateImages(Request $request, string $id)
{
    try {
        $product = Product::with('images')->findOrFail($id);

        // ✅ تتبع بداية الطلب
        Log::info('🔍 Full request payload', $request->all());

        // ✅ تحقق من وجود delete_images
        $deleteIds = collect($request->input('delete_images', []));
        Log::info('🗑️ IDs requested for deletion', $deleteIds->toArray());

        // ✅ تحقق من عدد الصور الكلي
        $currentImageCount = $product->images->count();
        Log::info("📸 Current image count: $currentImageCount");

        // ✅ منع حذف كل الصور
        if ($deleteIds->count() >= $currentImageCount) {
            Log::warning('⚠️ Attempted to delete all images!');
            return response()->json([
                'message' => 'At least one image must remain.',
            ], 422);
        }

        // ✅ حذف الصور المطلوبة
        foreach ($deleteIds as $imageId) {
            $image = $product->images()->find($imageId);
            if ($image) {
                Log::info("🚨 Deleting image ID: {$image->id}");

                if (Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                    Log::info("🗑️ Deleted file from storage: {$image->image_url}");
                } else {
                    Log::warning("❗ File not found in storage: {$image->image_url}");
                }

                $image->delete();
            } else {
                Log::warning("❗ Image ID $imageId not found or doesn't belong to this product.");
            }
        }

        // ✅ استبدال الصور الحالية
        if ($request->hasFile('replace_images')) {
            foreach ($request->file('replace_images') as $imageId => $newImageFile) {
                if ($newImageFile) {
                    $oldImage = $product->images()->find($imageId);
                    if ($oldImage) {
                        Storage::disk('public')->delete($oldImage->image_url);
                        $oldImage->delete();
                        Log::info("🔄 Replaced image ID: $imageId");

                        $path = $newImageFile->store('products', 'public');
                        $product->images()->create(['image_url' => $path]);
                        Log::info("✅ Stored new replacement image: $path");
                    }
                }
            }
        }

        // ✅ إضافة صور جديدة
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $newImage) {
                $path = $newImage->store('products', 'public');
                $product->images()->create(['image_url' => $path]);
                Log::info("➕ Added new image: $path");
            }
        }

        // ✅ الرد النهائي
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Images updated successfully!']);
        }

        return redirect()->route('merchant.products.show', $product->id)
                         ->with('success', 'Images updated successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('❌ Validation failed', $e->errors());
        if ($request->expectsJson()) {
            return response()->json(['errors' => $e->errors(), 'message' => 'Validation error.'], 422);
        }
        return redirect()->back()->withErrors($e->errors());
    } catch (\Exception $e) {
        Log::error('💥 Unexpected error while updating images: ' . $e->getMessage());
        if ($request->expectsJson()) {
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
        return redirect()->back()->with('error', 'Error updating images.');
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
                    // بعد $product->delete();
                  $admins = User::where('user_type', 'admin')->get();

                foreach ($admins as $admin) {
                NotificationService::send(
                $admin->id,
                'The product "' . $product->name . '" was deleted by ' . auth()->user()->name . '.',
                'deleted_product',
                 url('/admin/products'),
                'warning',
                 auth()->id()
    );
}

            $product->delete();


            return redirect()->route('merchant.products.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error while deleting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the product.');
        }
    }



    public function disableProduct(Request $request, Product $product)
    {
        $request->validate([
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after:from_date',
            'reason' => 'required|string|min:5',
        ]);

        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $toDate = Carbon::parse($request->to_date)->endOfDay();

        $product->update([
            'status' => 'maintenance',
            'maintenance_from' => $fromDate,
            'maintenance_to' => $toDate,
        ]);

        // 🔔 Send notification to all admins
        $adminIds = User::where('user_type', 'admin')->pluck('id');
        foreach ($adminIds as $adminId) {
            NotificationService::send(
                $adminId,
                "The product '{$product->name}' by merchant '{$product->user->name}' has been set to maintenance from {$fromDate->format('Y-m-d')} to {$toDate->format('Y-m-d')}.",
                'product_maintenance',
                url("/admin/products/{$product->id}"),
                'normal',
                auth()->id()
            );
        }

        // جلب الحجوزات المرتبطة
        $reservations = Reservation::where('product_id', $product->id)
            ->where('status', '!=', 'cancelled')
            ->whereDate('end_date', '>=', Carbon::today())
            ->get();

        // تحديث حالة كل حجز أولاً
        foreach ($reservations as $reservation) {
            $this->checkAndUpdateStatus($reservation);
        }

        // إعادة تحميل الحجوزات التي status = not_started وتبدأ خلال فترة التعطيل
        $affectedReservations = Reservation::with('user')
            ->where('product_id', $product->id)
            ->where('status', 'not_started')
            ->whereDate('start_date', '>=', $fromDate)
            ->whereDate('start_date', '<=', $toDate)
            ->get();

        foreach ($affectedReservations as $reservation) {
            $startDate = Carbon::parse($reservation->start_date);
            $endDate = Carbon::parse($reservation->end_date);
            $diff = $startDate->diffInDays($toDate);


            $daysNeeded = $endDate->diffInDays($startDate) + 1;

            if ($diff <= 5) {

                $nextAvailable = $this->getNextAvailablePeriod($product, $toDate->copy()->addDays(2), $daysNeeded);


                Mail::to($reservation->user->email)->send(new SuggestDelayWithApproval(
                    $reservation,
                    $product,
                    $nextAvailable,
                    $request->reason
                ));
            } else {

                $reservation->update(['status' => 'cancelled']);

                $suggestedProducts = Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->where('status', 'available')
                    ->where('name', 'like', '%' . $product->name . '%')
                    ->limit(3)
                    ->get();


                Mail::to($reservation->user->email)->send(new ReservationCancelledWithSuggestions(
                    $reservation,
                    $product,
                    $suggestedProducts
                ));
            }
        }

        return response()->json(['message' => 'Product disabled and all affected reservations processed.']);
    }

        //   Email Functhins

    public function getNextAvailablePeriod(Product $product, Carbon $startFrom, int $daysNeeded): ?array
    {
        $reservations = Reservation::where('product_id', $product->id)
            ->where('status', '!=', 'cancelled')
            ->where('end_date', '>=', Carbon::today())
            ->get();

        $dateCounts = [];

        foreach ($reservations as $res) {
            $resStart = Carbon::parse($res->start_date);
            $resEnd = Carbon::parse($res->end_date);

            for ($date = $resStart->copy(); $date->lte($resEnd); $date->addDay()) {
                $key = $date->toDateString();
                $dateCounts[$key] = ($dateCounts[$key] ?? 0) + $res->quantity;
            }
        }

        $checkDate = $startFrom->copy();

        while (true) {
            $candidateStart = $checkDate->copy();
            $candidateEnd = $candidateStart->copy()->addDays($daysNeeded - 1);
            $isAvailable = true;

            for ($date = $candidateStart->copy(); $date->lte($candidateEnd); $date->addDay()) {
                $key = $date->toDateString();
                $reserved = $dateCounts[$key] ?? 0;

                if (
                    $product->maintenance_start && $product->maintenance_end &&
                    $date->between(Carbon::parse($product->maintenance_start), Carbon::parse($product->maintenance_end))
                ) {
                    $isAvailable = false;
                    break;
                }

                if ($reserved >= $product->quantity) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                return [
                    'start_date' => $candidateStart->toDateString(),
                    'end_date'   => $candidateEnd->toDateString(),
                ];
            }

            $checkDate->addDay();

            if ($checkDate->diffInDays($startFrom) > 180) {
                break;
            }
        }

        return null;
    }

    public function toggleStatus(Product $product)
    {
        if ($product->status === 'maintenance') {
            $product->update([
                'status' => 'available',
                'maintenance_start' => null,
                'maintenance_end' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Product status updated successfully!');
    }

    public function approveDelay(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $product = $reservation->product;

        $token = $request->query('token');

        // ✅ التحقق من وجود التوكن وعدم استخدامه
        $actionLog = ReservationActionLog::where('token', $token)->first();

        if (!$actionLog || $actionLog->used_at) {
            return response()->view('sweet', [
                'type' => 'error',
                'title' => 'Unauthorized Action',
                'message' => 'This link has already been used or is no longer valid.',
            ]);

        }

        $start = $request->query('start_date');
        $end = $request->query('end_date');

        if (!$start || !$end) {
            abort(400, 'Missing suggested dates.');
        }

        $daysNeeded = Carbon::parse($end)->diffInDays(Carbon::parse($start)) + 1;
        $recheck = $this->getNextAvailablePeriod($product, Carbon::parse($start), $daysNeeded);

        if (!$recheck || $recheck['start_date'] !== $start || $recheck['end_date'] !== $end) {
            $suggested = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'available')
                ->where('name', 'like', '%' . $product->name . '%')
                ->limit(3)
                ->get();

            $reservation->update(['status' => 'cancelled']);

            // ✅ حرق التوكن وتسجيله كمرفوض تلقائي
            $actionLog->update([
                'action' => 'rejected',
                'used_at' => now(),
            ]);

            Mail::to($reservation->user->email)->send(new ReservationCancelledWithSuggestions(
                $reservation, $product, $suggested
            ));

            return response()->view('sweet', [
                'type' => 'warning',
                'title' => 'No Longer Available',
                'message' => 'The suggested dates are no longer available. Your reservation has been cancelled.',
            ]);
        }

        // ✅ تحديث الحجز
        $reservation->update([
            'start_date' => $start,
            'end_date' => $end,
        ]);

        // ✅ تحديث التوكن كمستخدم
        $actionLog->update([
            'action' => 'approved',
            'used_at' => now(),
        ]);

        Mail::to($reservation->user->email)->send(new BookingConfirmed(
            $reservation->user, $product, $reservation
        ));

        return response()->view('sweet', [
            'type' => 'success',
            'title' => 'Reservation Updated!',
            'message' => 'Your reservation has been updated to the new dates.',
        ]);
    }

    public function rejectDelay(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $product = $reservation->product;
        $token = $request->query('token');

        $actionLog = ReservationActionLog::where('token', $token)->first();

        if (!$actionLog || $actionLog->used_at) {
            return response()->view('sweet', [
                'type' => 'error',
                'title' => 'Unauthorized Action',
                'message' => 'This link has already been used or is no longer valid.',
            ]);

        }

        $reservation->update([
            'status' => 'cancelled',
            'app_fee' => 0,
        ]);

        $actionLog->update([
            'action' => 'rejected',
            'used_at' => now(),
        ]);

        $suggested = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'available')
            ->where('name', 'like', '%' . $product->name . '%')
            ->limit(3)
            ->get();

        Mail::to($reservation->user->email)->send(new ReservationCancelledWithSuggestions(
            $reservation, $product, $suggested
        ));

        return response()->view('sweet', [
            'type' => 'error',
            'title' => 'Reservation Cancelled!',
            'message' => 'Your reservation has been cancelled as requested.',
        ]);
    }


}
