<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    // OTP Verification routes
    Route::get('verify-otp', [RegisteredUserController::class, 'showOtpForm'])
        ->name('otp.verify');

    Route::post('verify-otp', [RegisteredUserController::class, 'verifyOtp']);

    Route::post('resend-otp', [RegisteredUserController::class, 'resendOtp'])
        ->name('otp.resend');

    Route::post('resend-otp-form', [RegisteredUserController::class, 'resendOtpForm'])
        ->name('otp.resend.form');

    // Test registration (bypasses OTP - for testing email verification)
    Route::post('test-register', [App\Http\Controllers\Auth\TestRegisterController::class, 'testRegister'])
        ->name('test.register');

    // Simple OTP registration (logs OTP instead of sending SMS)
    Route::post('simple-register', [App\Http\Controllers\Auth\SimpleOtpController::class, 'simpleRegister'])
        ->name('simple.register');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');


    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Manual email verification for development
    Route::post('manual-verify-email', function () {
        if (!app()->environment('local')) {
            abort(403, 'Only available in development mode');
        }

        $user = auth()->user();
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->route('dashboard')->with('success', 'Email verified successfully (manual verification)!');
    })->name('manual.verify.email');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('logout', function () {
        return redirect()->route('login')->with('message', __('Please use the logout button to sign out.'));
    });
});
