<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlacesController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Places API routes
    Route::get('/places/search', [PlacesController::class, 'search']);
    Route::get('/places/reverse-geocode', [PlacesController::class, 'reverseGeocode']);
    Route::get('/places/details', [PlacesController::class, 'placeDetails']);
});

// 臨時測試路由（不需要認證）
Route::get('/places/test-search', [PlacesController::class, 'search']);
Route::get('/places/test-reverse-geocode', [PlacesController::class, 'reverseGeocode']);
Route::get('/places/test-details', [PlacesController::class, 'placeDetails']);

// 簡單測試路由
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});