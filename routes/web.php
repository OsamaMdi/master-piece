<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SystemCheckController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ReportsController;
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


Route::get('/reservation/approve-delay/{id}', [ProductController::class, 'approveDelay'])->name('reservation.approve_delay');
Route::get('/reservation/reject-delay/{id}', [ProductController::class, 'rejectDelay'])->name('reservation.reject_delay');



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
Route::middleware(['auth', 'block.check', 'user.only'])->group(function () {
    Route::get('/my-activity', [UserFrontendController::class, 'indexActivity'])->name('user.activity');
    Route::post('/reservations/{id}/cancel', [UserFrontendController::class, 'cancelReservation'])->name('reservations.cancel');
    Route::post('/reservations/{id}/report', [UserFrontendController::class, 'report'])->name('reservations.report');
    Route::patch('/reports/{report}/resolve', [ReportsController::class, 'resolveReport'])->name('reports.resolve');
});


// Auth routes (Login, Register, Forgot Password, etc)
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Public Website Routes - User Views
|--------------------------------------------------------------------------
*/
Route::get('/', [UserFrontendController::class, 'home'])->name('home');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');
Route::post('/favorites/{product}/toggle', [UserFrontendController::class, 'toggle'])->name('favorites.toggle')->middleware('auth');
Route::get('/tools', [UserFrontendController::class, 'allTools'])->name('tools.all');
Route::get('/products/category/{id}', [UserFrontendController::class, 'showByCategory'])->name('products.by.category');
Route::get('/products/show/{id}', [UserFrontendController::class, 'showByCategory'])->name('products.show');
/* Route::view('/room', 'users.room')->name('room'); */
/* Route::view('/about', 'users.about')->name('about'); */
Route::view('/contact', 'users.contact')->name('contact');
Route::get('/products/{id}', [UserFrontendController::class, 'showProduct'])->name('user.products.show');
Route::post('/products/{id}/reviews', [UserFrontendController::class, 'storeReview'])->name('user.products.reviews.store')->middleware('auth');;
Route::get('/user-feedback', [UserFrontendController::class, 'userFeedback'])->name('about');
Route::post('/website-reviews/store', [UserFrontendController::class, 'storeWebsiteReview'])
    ->name('user.websiteReview.store')
    /* ->middleware('auth') */;

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
    Route::patch('products/{id}/cancel', [AdminProductController::class, 'blockWithCancel'])->name('products.cancel');
    Route::patch('products/{product}/block', [AdminProductController::class, 'block'])->name('products.block');   // << جديد
    Route::patch('products/{product}/unblock', [AdminProductController::class, 'unblock'])->name('products.unblock'); // << جديد
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
    Route::resource('reports', ReportsController::class)->only(['index', 'show', 'destroy']);
    Route::patch('reports/{report}/status', [ReportsController::class, 'updateStatus'])->name('reports.updateStatus');
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
    Route::patch('/{product}/disable', [ProductController::class, 'disableProduct'])->name('products.disable');
    Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
});





// Public Routes (for users and merchants)
Route::post('/reports', [ReportsController::class, 'sendReport'])->name('reports.send');
Route::patch('/reports/{report}/resolve', [ReportsController::class, 'resolveReport'])->name('reports.resolve');

