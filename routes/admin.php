<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

// Admin routes (protected by auth and admin middleware)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('trips', TripController::class)->parameters(['trips' => 'trip:id']);
    Route::resource('users', UserController::class)->parameters(['users' => 'user:id']);
    Route::resource('coupons', CouponController::class)->parameters(['coupons' => 'coupon:id']);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show']);

    Route::prefix('payment')->group(function () {
        Route::post('/', [PaymentController::class, 'approve'])->name('payment.approve');
    });
});
