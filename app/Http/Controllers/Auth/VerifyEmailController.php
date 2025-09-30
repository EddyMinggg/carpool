<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use AWS\CRT\HTTP\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke($id, $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->markEmailAsVerified();

        return redirect()->route('email-verified');
    }
}
