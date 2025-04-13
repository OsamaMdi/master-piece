<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\merchants\ProductController;
use App\Http\Controllers\IdentityController;
use App\Http\Controllers\SystemCheckController;

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

Route::view('/', 'users.index')->name('index');
Route::view('/room', 'users.room')->name('room');
Route::view('/about', 'users.about')->name('about');
Route::view('/single-room', 'users.single-room')->name('single-room');
Route::view('/contact', 'users.contact')->name('contact');

/*
|--------------------------------------------------------------------------
| Owner Website Routes - Owner Views
|--------------------------------------------------------------------------
*/

Route::prefix('owner')->group(function () {
    Route::view('/', 'owner.index')->name('Oindex');
    Route::view('/room', 'owner.room')->name('Oroom');
    Route::view('/about', 'owner.about')->name('Oabout');
    Route::view('/single-room', 'owner.single-room')->name('Osingle-room');
    Route::view('/blog', 'owner.blog')->name('Oblog');
    Route::view('/single-blog', 'owner.single-blog')->name('Osingle-blog');
    Route::view('/contact', 'owner.contact')->name('Ocontact');
});

/*
|--------------------------------------------------------------------------
| Merchant Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('merchant')->name('merchant.')->middleware('auth')->group(function () {
    Route::get('/', [MerchantController::class, 'dashboard'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::get('/{id}/reservations', [ProductController::class, 'showReservations'])->name('reservations');
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
