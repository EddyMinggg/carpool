<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;


Route::get('/', [TripController::class, 'dashboard'])->name('dashboard');

Route::get('/verified', function () {
    return view('email-verified');
})->name('email-verified');

Route::prefix('trips')->middleware(['auth', 'verified', 'prevent.driver.trips'])->group(function () {
    Route::get('/', [TripController::class, 'index'])->name('trips');
    Route::get('/{id}', [TripController::class, 'show'])->name('trips.show');
    Route::delete('/{trip}/leave', [TripController::class, 'leave'])->name('trips.leave');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('payment')->middleware('prevent.driver.trips')->group(function () {
        Route::post('/', [PaymentController::class, 'store'])->name('payment.create');
        Route::get('/{id}', [PaymentController::class, 'show'])->name('payment.code');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Driver routes - will be accessible later via direct login
    Route::prefix('driver')->group(function () {
        Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('driver.dashboard');
        Route::get('/my-trips', [DriverController::class, 'myTrips'])->name('driver.my-trips');
        Route::post('/trips/{trip}/assign', [DriverController::class, 'assignTrip'])->name('driver.assign-trip');
        Route::post('/assignments/{assignment}/confirm', [DriverController::class, 'confirmTrip'])->name('driver.confirm-trip');
        Route::post('/assignments/{assignment}/cancel', [DriverController::class, 'cancelTrip'])->name('driver.cancel-trip');
        Route::post('/assignments/{assignment}/complete', [DriverController::class, 'completeTrip'])->name('driver.complete-trip');
    });
});

Route::get('lang', [LanguageController::class, 'change'])->name("change.lang");
Route::post('/set-session', [SessionController::class, 'setSession'])->name('session.set');
Route::get('/map', function () {
    return view('map');
})->name('map');

require __DIR__ . '/auth.php';
