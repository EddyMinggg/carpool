<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentConfirmationController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

// Admin routes (protected by auth and admin middleware)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('trips', TripController::class)->parameters(['trips' => 'trip:id']);
    
    // Trip pending review routes (for 2-passenger trips)
    Route::get('trips/pending/review', [TripController::class, 'pendingReview'])->name('trips.pending-review');
    Route::post('trips/{trip}/confirm-surcharge', [TripController::class, 'confirmWithSurcharge'])->name('trips.confirm-surcharge');
    
    Route::resource('users', UserController::class)->parameters(['users' => 'user:id']);
    
    // User activation/deactivation routes
    Route::post('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    
    Route::resource('drivers', DriverController::class)->parameters(['drivers' => 'driver:id']);
    
    // Driver activation/deactivation routes
    Route::post('drivers/{driver}/activate', [DriverController::class, 'activate'])->name('drivers.activate');
    Route::post('drivers/{driver}/deactivate', [DriverController::class, 'deactivate'])->name('drivers.deactivate');
    
    Route::resource('coupons', CouponController::class)->parameters(['coupons' => 'coupon:id']);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show']);

    Route::prefix('payment')->group(function () {
        Route::post('/', [PaymentController::class, 'approve'])->name('payment.approve');
    });

    // Payment Confirmation routes
    Route::prefix('payment-confirmation')->name('payment-confirmation.')->group(function () {
        Route::get('/', [PaymentConfirmationController::class, 'global'])->name('global'); // New global search page
        Route::get('/trip/{trip}', [PaymentConfirmationController::class, 'index'])->name('index');
        Route::get('/payment/{payment}', [PaymentConfirmationController::class, 'show'])->name('show');
        Route::post('/payment/{payment}', [PaymentConfirmationController::class, 'confirm'])->name('confirm');
        Route::post('/trip/{trip}/bulk-confirm', [PaymentConfirmationController::class, 'bulkConfirm'])->name('bulk-confirm');
        Route::get('/search', [PaymentConfirmationController::class, 'search'])->name('search'); // AJAX search endpoint
        Route::get('/statistics', [PaymentConfirmationController::class, 'statistics'])->name('statistics');
    });
});
