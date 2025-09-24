<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;


Route::get('/', [TripController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('lang', [LanguageController::class, 'change'])->name("change.lang");

    Route::prefix('trips')->group(function () {
        Route::get('/', [TripController::class, 'index'])->name('trips');
        Route::post('/create', [TripController::class, 'store'])->name('trips.store');
        Route::get('/{id}', [TripController::class, 'show'])->name('trips.show');
        Route::post('/{trip}/join', [TripController::class, 'join'])->name('trips.join');
        Route::delete('/{trip}/leave', [TripController::class, 'leave'])->name('trips.leave');
        Route::post('/{trip}/depart-now', [TripController::class, 'departNow'])->name('trips.depart-now');
    });

    Route::prefix('payment')->group(function () {
        Route::post('/', [PaymentController::class, 'store'])->name('payment.create');
        Route::get('/{id}', [PaymentController::class, 'show'])->name('payment.code');
    });

    Route::post('/set-session', [SessionController::class, 'setSession'])->name('session.set');
});

require __DIR__ . '/auth.php';
