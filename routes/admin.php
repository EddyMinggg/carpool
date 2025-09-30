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
    Route::resource('users', UserController::class)->parameters(['users' => 'user:id']);
    Route::resource('drivers', DriverController::class)->parameters(['drivers' => 'driver:id']);
    Route::resource('coupons', CouponController::class)->parameters(['coupons' => 'coupon:id']);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show']);

    Route::prefix('payment')->group(function () {
        Route::post('/', [PaymentController::class, 'approve'])->name('payment.approve');
    });

    // Payment Confirmation routes
    Route::prefix('payment-confirmation')->name('payment-confirmation.')->group(function () {
        Route::get('/trip/{trip}', [PaymentConfirmationController::class, 'index'])->name('index');
        Route::get('/payment/{payment}', [PaymentConfirmationController::class, 'show'])->name('show');
        Route::post('/payment/{payment}', [PaymentConfirmationController::class, 'confirm'])->name('confirm');
        Route::post('/trip/{trip}/bulk-confirm', [PaymentConfirmationController::class, 'bulkConfirm'])->name('bulk-confirm');
        Route::get('/statistics', [PaymentConfirmationController::class, 'statistics'])->name('statistics');
    });
});
