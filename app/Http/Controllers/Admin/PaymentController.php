<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function approve(Request $request)
    {
        $payment = Payment::where('reference_code', $request->input('reference_code'))->first();
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment record not found.');
        }

        if ($payment->paid) {
            return redirect()->back()->with('error', 'Payment is already approved.');
        }

        $payment->update([
            'paid' => true
        ]);

        return redirect()->back()->with('success', 'Payment approved.');
    }
}
