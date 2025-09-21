<?php

use App\Http\Controllers\SuperAdmin\AdminController;
use Illuminate\Support\Facades\Route;

// Super Admin routes (protected by auth and super_admin middleware)
Route::prefix('super-admin')->middleware(['auth', 'super_admin'])->name('super-admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('super-admin.admins.index');
    })->name('dashboard');
    
    Route::resource('admins', AdminController::class)->parameters(['admins' => 'admin:id']);
});