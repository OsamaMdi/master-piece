<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\user\UserController as UserFrontendController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\SystemCheckController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\merchants\ProductController;
use App\Http\Controllers\admin\AdminProductController;
use App\Http\Controllers\Admin\WebsiteReviewController;
use App\Http\Controllers\merchants\ReservationController;
use App\Http\Controllers\admin\AdminReservationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Check if Google Credentials file exists (Moved to SystemCheckController)
Route::get('/check-json', [SystemCheckController::class, 'checkGoogleCredentials'])->name('system.check-json');

// Upload and scan ID image via Controller
Route::post('/upload-id-ajax', [IdentityController::class, 'upload'])->name('upload.id.ajax');

// Dashboard for authenticated users
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (Login, Register, Forgot Password, etc)
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Public Website Routes - User Views
|--------------------------------------------------------------------------
*/
Route::get('/', [UserFrontendController::class, 'home'])->name('home');
Route::get('/tools', [UserFrontendController::class, 'allTools'])->name('tools.all');
Route::get('/products/category/{id}', [UserFrontendController::class, 'showByCategory'])->name('products.by.category');
Route::get('/products/show/{id}', [UserFrontendController::class, 'showByCategory'])->name('products.show');
Route::view('/room', 'users.room')->name('room');
Route::view('/about', 'users.about')->name('about');
Route::view('/single-room', 'users.single-room')->name('single-room');
Route::view('/contact', 'users.contact')->name('contact');
Route::get('/products/{id}', [UserFrontendController::class, 'showProduct'])->name('user.products.show');
Route::post('/products/{id}/reviews', [UserFrontendController::class, 'storeReview'])->name('user.products.reviews.store');
/*
|--------------------------------------------------------------------------
| admin Website Routes - admin Views
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [MerchantController::class, 'adminDashboard'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::get('/{id}/reservations', [AdminReservationController::class, 'showProductReservations'])->name('product.reservations');
    Route::get('/reservations', [AdminReservationController::class, 'showReservations'])->name('reservations');
    Route::get('/reservation/{id}/details', [AdminReservationController::class, 'showReservationDetails'])->name('reservation.details');
   /*  Route::post('/products/{product}/update-images', [AdminProductController::class, 'updateImages'])->name('products.updateImages'); */
    Route::post('products/upload-image', [AdminProductController::class, 'uploadImage'])->name('products.uploadImage');

    Route::patch('products/{id}/block', [AdminProductController::class, 'blockWithCancel'])->name('products.block');


    Route::resource('users', UserController::class);
    Route::post('users/upload-identity', [UserController::class, 'uploadIdentity'])->name('users.upload.identity');

    Route::resource('categories', CategoryController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('website-reviews', WebsiteReviewController::class);

    Route::get('products/{product}/images/count', function ($productId) {
        $product = App\Models\Product::findOrFail($productId);
        return response()->json([
            'count' => $product->images()->count(),
        ]);
    });
});


/*
|--------------------------------------------------------------------------
| Merchant Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('merchant')->name('merchant.')->middleware('auth')->group(function () {
    Route::get('/', [MerchantController::class, 'dashboard'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::get('/{id}/reservations', [ReservationController::class, 'showProductReservations'])->name('product.reservations');
    Route::get('/reservations', [ReservationController::class, 'showReservations'])->name('reservations');
    Route::get('/reservation/{id}/details', [ReservationController::class, 'showReservationDetails'])->name('reservation.details');
    Route::post('/products/{product}/update-images', [ProductController::class, 'updateImages'])->name('products.updateImages');
    Route::post('products/upload-image', [ProductController::class, 'uploadImage'])->name('products.uploadImage');
    Route::patch('/reservation/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
    Route::get('products/{product}/images/count', function ($productId) {
        $product = App\Models\Product::findOrFail($productId);
        return response()->json([
            'count' => $product->images()->count(),
        ]);
    });
});
