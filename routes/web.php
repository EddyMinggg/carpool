<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('lang', [LanguageController::class, 'change'])->name("change.lang");

    Route::prefix('trips')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('trips');
        Route::post('/create', [OrderController::class, 'store'])->name('trips.store');
    });
});

require __DIR__ . '/auth.php';

// Admin routes (protected by auth and admin middleware)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('trips', TripController::class)->parameters(['trips' => 'trip:id']);
    Route::resource('users', UserController::class)->parameters(['users' => 'user:id']);
    Route::resource('coupons', CouponController::class)->parameters(['coupons' => 'coupon:id']);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show'])->parameters(['orders' => 'order:id']);
});
