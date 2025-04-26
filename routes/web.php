<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SystemCheckController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\merchants\ProductController;
use App\Http\Controllers\admin\AdminProductController;
use App\Http\Controllers\Admin\WebsiteReviewController;
use App\Http\Controllers\admin\AdminReservationController;
use App\Http\Controllers\user\UserController as UserFrontendController;
use App\Http\Controllers\merchants\ReservationController as MerchantReservationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/blocked', function () {
    return view('users.blocked');
})->name('blocked.page');


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
Route::view('/contact', 'users.contact')->name('contact');
Route::get('/products/{id}', [UserFrontendController::class, 'showProduct'])->name('user.products.show');
Route::post('/products/{id}/reviews', [UserFrontendController::class, 'storeReview'])->name('user.products.reviews.store');
Route::get('/user-feedback', [UserFrontendController::class, 'userFeedback'])->name('user.feedback');
Route::post('/website-reviews/store', [UserFrontendController::class, 'storeWebsiteReview'])
    ->name('user.websiteReview.store')
    ->middleware('auth');

    Route::get('/live-search', [SearchController::class, 'liveSearch'])->name('live.search');


    // routes/api.php
Route::get('/reservations/unavailable-dates/{product}', [ReservationController::class, 'getUnavailableDates']);
Route::post('/rentals', [ReservationController::class, 'store'])->name('user.rentals.store');


/*
|--------------------------------------------------------------------------
| admin Website Routes - admin Views
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'block.check', 'admin.only'])->group(function () {
    Route::get('/', [MerchantController::class, 'adminDashboard'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::get('/{id}/reservations', [AdminReservationController::class, 'showProductReservations'])->name('product.reservations');
    Route::get('/reservations', [AdminReservationController::class, 'showReservations'])->name('reservations');
    Route::get('/reservation/{id}/details', [AdminReservationController::class, 'showReservationDetails'])->name('reservation.details');
    Route::post('products/upload-image', [AdminProductController::class, 'uploadImage'])->name('products.uploadImage');
    Route::patch('products/{id}/block', [AdminProductController::class, 'blockWithCancel'])->name('products.block');
    Route::get('/search', [SearchController::class, 'adminSearch'])->name('search');
    Route::resource('users', UserController::class);
    Route::post('users/upload-identity', [UserController::class, 'uploadIdentity'])->name('users.upload.identity');
    Route::resource('categories', CategoryController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('website-reviews', WebsiteReviewController::class);
    Route::get('products/{product}/images/count', function ($productId) {
        $product = Product::findOrFail($productId);
        return response()->json([
            'count' => $product->images()->count(),
        ]);
    });
    Route::patch('users/{user}/block', [UserController::class, 'block'])->name('users.block');
Route::patch('users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');

});


/*
|--------------------------------------------------------------------------
| Merchant Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('merchant')->name('merchant.')->middleware(['auth', 'block.check', 'merchant.only'])->group(function () {
    Route::get('/', [MerchantController::class, 'dashboard'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::get('/{id}/reservations', [MerchantReservationController::class, 'showProductReservations'])->name('product.reservations');
    Route::get('/reservations', [MerchantReservationController::class, 'showReservations'])->name('reservations');
    Route::get('/reservation/{id}/details', [MerchantReservationController::class, 'showReservationDetails'])->name('reservation.details');
    Route::post('/products/{product}/update-images', [ProductController::class, 'updateImages'])->name('products.updateImages');
    Route::post('products/upload-image', [ProductController::class, 'uploadImage'])->name('products.uploadImage');
    Route::patch('/reservation/{id}/cancel', [MerchantReservationController::class, 'cancel'])->name('reservation.cancel');
    Route::get('/search', [SearchController::class, 'merchantSearch'])->name('search');

    Route::get('products/{product}/images/count', function ($productId) {
        $product = Product::findOrFail($productId);
        return response()->json([
            'count' => $product->images()->count(),
        ]);
    });


    Route::view('/under-review', 'merchants.under_review')->name('under_review');
});

