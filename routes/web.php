<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\TripController;
use App\Models\Trip;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $trips = Trip::paginate(10);
    return view('dashboard', compact('trips'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('lang', [LanguageController::class, 'change'])->name("change.lang");

    Route::prefix('trips')->group(function () {
        Route::get('/', [TripController::class, 'index'])->name('trips');
        Route::post('/create', [TripController::class, 'store'])->name('trips.store');
    });
});

require __DIR__ . '/auth.php';
