<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Trip;
use App\Models\TripJoin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        // Update trip_joins payment_confirmation status
        $this->updateTripJoinPaymentStatus($payment);

        return redirect()->back()->with('success', 'Payment approved.');
    }

    /**
     * Update trip_joins payment_confirmation status for confirmed payments
     */
    private function updateTripJoinPaymentStatus(Payment $payment)
    {
        try {
            if ($payment->type === 'group_full_payment' && $payment->group_size > 1) {
                // For group bookings, update all trip_joins for this trip that match the payment criteria
                $updated = TripJoin::where('trip_id', $payment->trip_id)
                    ->where('payment_confirmation', false)
                    ->limit($payment->group_size)
                    ->update([
                        'payment_confirmation' => true
                    ]);
                
                Log::info('Group booking trip joins updated', [
                    'payment_id' => $payment->id,
                    'trip_id' => $payment->trip_id,
                    'group_size' => $payment->group_size,
                    'updated_count' => $updated,
                ]);
            } else {
                // For individual bookings, update the specific user's trip_join record
                TripJoin::where('trip_id', $payment->trip_id)
                    ->where('user_phone', $payment->user_phone)
                    ->update([
                        'payment_confirmation' => true
                    ]);
                
                Log::info('Individual trip join updated', [
                    'payment_id' => $payment->id,
                    'trip_id' => $payment->trip_id,
                    'user_phone' => $payment->user_phone,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to update trip join payment status', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
