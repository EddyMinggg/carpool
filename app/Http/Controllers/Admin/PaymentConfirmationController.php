<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripJoin;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentConfirmationController extends Controller
{
    /**
     * Display payment confirmation page for a specific trip
     */
    public function index(Trip $trip)
    {
        // Get all pending payments (not yet confirmed) for this trip
        $pendingPayments = Payment::with(['trip', 'user'])
            ->where('trip_id', $trip->id)
            ->where('paid', false)
            ->orderBy('type', 'asc') // Show deposits first, then remaining
            ->orderBy('created_at', 'desc')
            ->get();

        // Get confirmed payments for reference
        $confirmedPayments = Payment::with(['trip', 'user'])
            ->where('trip_id', $trip->id)
            ->where('paid', true)
            ->orderBy('type', 'asc') // Show deposits first, then remaining
            ->orderBy('updated_at', 'desc')
            ->get();

        // Calculate trip statistics
        $tripStats = [
            'total_members' => TripJoin::where('trip_id', $trip->id)->count(),
            'pending_deposits' => $pendingPayments->where('type', 'deposit')->count(),
            'confirmed_deposits' => $confirmedPayments->where('type', 'deposit')->count(),
            'pending_remaining' => $pendingPayments->where('type', 'remaining')->count(),
            'confirmed_remaining' => $confirmedPayments->where('type', 'remaining')->count(),
            'total_confirmed_amount' => $confirmedPayments->sum('amount'),
            'total_pending_amount' => $pendingPayments->sum('amount'),
        ];

        // Mobile device detection
        $userAgent = request()->header('User-Agent');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        return view('admin.payment-confirmation.index', compact(
            'trip',
            'pendingPayments',
            'confirmedPayments',
            'tripStats',
            'isMobile'
        ));
    }

    /**
     * Show individual payment confirmation form
     */
    public function show(Payment $payment)
    {
        // Check if payment is already confirmed
        if ($payment->paid) {
            return redirect()
                ->route('admin.payment-confirmation.index', $payment->trip)
                ->with('error', 'Payment has already been confirmed.');
        }

        // Load relationships
        $payment->load(['trip', 'user']);

        // Mobile device detection
        $userAgent = request()->header('User-Agent');
        $isMobile = preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);

        return view('admin.payment-confirmation.show', compact('payment', 'isMobile'));
    }

    /**
     * Confirm individual payment
     */
    public function confirm(Request $request, Payment $payment)
    {
        // Check if this is auto-confirm mode (simplified workflow)
        $isAutoConfirm = $request->has('auto_confirm');
        
        if ($isAutoConfirm) {
            // Auto-confirm mode - no validation required
            $referenceCode = $this->generateReferenceCode($payment);
        } else {
            // Manual mode - validate input
            $request->validate([
                'reference_code' => 'required|string|max:50|unique:payments,reference_code,' . $payment->id,
                'notes' => 'nullable|string|max:1000',
                'confirm_payment' => 'required|accepted',
            ]);
            $referenceCode = $request->reference_code;
        }

        try {
            // Update main payment confirmation
            $payment->update([
                'reference_code' => $referenceCode,
                'paid' => true,
                'updated_at' => now(),
            ]);

            $confirmedCount = 1;

            // No child payments to process since each group booking has only one payment record

            // Update trip_joins payment_confirmation status for all related users
            $this->updateTripJoinPaymentStatus($payment);

            // Send email notification
            // $this->sendPaymentConfirmationEmail($payment, $request->notes);

            Log::info('Payment(s) confirmed', [
                'main_payment_id' => $payment->id,
                'trip_id' => $payment->trip_id,
                'user_phone' => $payment->user_phone,
                'type' => $payment->type,
                'amount' => $payment->amount,
                'reference_code' => $request->reference_code,
                'confirmed_by' => Auth::id(),
                'group_booking' => $payment->passengers > 1,
                'total_confirmed' => $confirmedCount,
            ]);

            $message = $confirmedCount === 1 
                ? 'Payment confirmed successfully.'
                : "Group booking confirmed successfully ({$confirmedCount} passengers).";

            return redirect()
                ->route('admin.payment-confirmation.index', $payment->trip)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Payment confirmation failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to confirm payment. Please try again.']);
        }
    }

    /**
     * Bulk confirm multiple payments
     */
    public function bulkConfirm(Request $request, Trip $trip)
    {
        $request->validate([
            'selected_payments' => 'required|array|min:1',
            'selected_payments.*' => 'exists:payments,id',
            'confirmations' => 'required|array',
            'confirmations.*.reference_code' => 'required|string|max:50',
        ]);

        $confirmed = 0;
        $errors = [];

        foreach ($request->selected_payments as $paymentId) {
            try {
                $payment = Payment::findOrFail($paymentId);

                // Skip if already confirmed
                if ($payment->paid) {
                    continue;
                }

                // Find the reference code for this payment
                $referenceCode = null;
                foreach ($request->confirmations as $confirmation) {
                    if (isset($confirmation['trip_join_id']) && $confirmation['trip_join_id'] == $paymentId) {
                        $referenceCode = $confirmation['reference_code'];
                        break;
                    }
                }

                if (!$referenceCode) {
                    $errors[] = "No reference code provided for payment #{$payment->id}";
                    continue;
                }

                // Check for duplicate reference codes
                $existingPayment = Payment::where('reference_code', $referenceCode)
                    ->where('id', '!=', $paymentId)
                    ->first();

                if ($existingPayment) {
                    $errors[] = "Reference code '{$referenceCode}' already used for payment #{$existingPayment->id}";
                    continue;
                }

                // Confirm payment
                $payment->update([
                    'reference_code' => $referenceCode,
                    'paid' => true,
                    'updated_at' => now(),
                ]);

                // Update trip_joins payment_confirmation status
                $this->updateTripJoinPaymentStatus($payment);

                // Send email notification
                $this->sendPaymentConfirmationEmail($payment);

                $confirmed++;
            } catch (\Exception $e) {
                $errors[] = "Failed to confirm payment #{$paymentId}: " . $e->getMessage();
            }
        }

        $message = "Successfully confirmed {$confirmed} payments.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(' ', $errors);
        }

        return redirect()
            ->route('admin.payment-confirmation.index', $trip)
            ->with($confirmed > 0 ? 'success' : 'error', $message);
    }

    /**
     * Send payment confirmation email to user
     */
    private function sendPaymentConfirmationEmail(Payment $payment, string $adminNotes = null)
    {
        try {
            $payment->load(['user', 'trip']);

            Mail::send('emails.payment-confirmed', [
                'payment' => $payment,
                'user' => $payment->user,
                'trip' => $payment->trip,
                'adminNotes' => $adminNotes,
                'paymentType' => ucfirst($payment->type), // 'Deposit' or 'Remaining'
            ], function ($message) use ($payment) {
                $message->to($payment->user->email, $payment->user->username)
                    ->subject('Payment Confirmed - ' . ucfirst($payment->type) . ' for Trip #' . $payment->trip->id);
            });

            Log::info('Payment confirmation email sent', [
                'payment_id' => $payment->id,
                'user_email' => $payment->user->email,
                'type' => $payment->type,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmation email', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get payment statistics for dashboard
     */
    public function statistics()
    {
        $stats = [
            'total_payments' => Payment::count(),
            'confirmed_payments' => Payment::where('paid', true)->count(),
            'pending_payments' => Payment::where('paid', false)->count(),
            'total_amount_confirmed' => Payment::where('paid', true)->sum('amount'),
            'deposits_confirmed' => Payment::where('paid', true)->where('type', 'deposit')->count(),
            'remaining_confirmed' => Payment::where('paid', true)->where('type', 'remaining')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Check if the request is from a mobile device
     */
    private function isMobileDevice(Request $request)
    {
        $userAgent = $request->header('User-Agent');
        return preg_match('/(android|iphone|ipad|mobile)/i', $userAgent);
    }

    /**
     * Generate a unique reference code for payment
     */
    private function generateReferenceCode(Payment $payment)
    {
        $timestamp = now()->format('ymdHi'); // YYMMDDHHmm
        $tripId = str_pad($payment->trip_id, 3, '0', STR_PAD_LEFT);
        $paymentId = str_pad($payment->id, 4, '0', STR_PAD_LEFT);
        
        return "REF{$tripId}{$paymentId}{$timestamp}";
    }

    /**
     * Update trip_joins payment_confirmation status for confirmed payments
     */
    private function updateTripJoinPaymentStatus(Payment $payment)
    {
        try {
            if ($payment->type === 'group_full_payment' && $payment->group_size > 1) {
                // For group bookings, update all trip_joins for this trip that match the payment criteria
                // Since we only have one payment for the whole group, we need to find all related trip_joins
                $updated = TripJoin::where('trip_id', $payment->trip_id)
                    ->where('payment_confirmation', false)
                    ->limit($payment->group_size) // Only update the number of people in this group
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
            // Don't throw exception here to avoid breaking payment confirmation
        }
    }

    /**
     * Global payment search and confirmation page
     */
    public function global(Request $request)
    {
        // Detect if mobile device
        $isMobile = $this->isMobileDevice($request);

        $query = Payment::with(['trip', 'user']);

        // Apply filters if provided
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('user_phone', 'like', "%{$search}%")
                  ->orWhere('reference_code', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('trip', function($tripQuery) use ($search) {
                      $tripQuery->where('id', 'like', "%{$search}%")
                               ->orWhere('dropoff_location', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->get('status') === 'pending') {
                $query->where('paid', false);
            } elseif ($request->get('status') === 'confirmed') {
                $query->where('paid', true);
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Order by most recent and pending first
        $payments = $query->orderByRaw('paid ASC') // Pending first
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);

        // Calculate statistics
        $stats = [
            'total_pending' => Payment::where('paid', false)->count(),
            'total_confirmed' => Payment::where('paid', true)->count(),
            'pending_amount' => Payment::where('paid', false)->sum('amount'),
            'confirmed_amount' => Payment::where('paid', true)->sum('amount'),
        ];

        return view('admin.payment-confirmation.global', compact('payments', 'stats', 'isMobile'));
    }

    /**
     * AJAX search endpoint for quick payment lookup
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        if (!$search || strlen($search) < 2) {
            return response()->json([]);
        }

        $payments = Payment::with(['trip', 'user'])
            ->where(function($q) use ($search) {
                $q->where('user_phone', 'like', "%{$search}%")
                  ->orWhere('reference_code', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhereHas('trip', function($tripQuery) use ($search) {
                      $tripQuery->where('id', 'like', "%{$search}%")
                               ->orWhere('dropoff_location', 'like', "%{$search}%");
                  });
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($payments->map(function($payment) {
            return [
                'id' => $payment->id,
                'user_phone' => $payment->user_phone,
                'amount' => $payment->amount,
                'type' => $payment->type,
                'paid' => $payment->paid,
                'reference_code' => $payment->reference_code,
                'trip_id' => $payment->trip->id,
                'trip_destination' => $payment->trip->dropoff_location,
                'created_at' => $payment->created_at->format('Y-m-d H:i'),
                'url' => route('admin.payment-confirmation.show', $payment),
            ];
        }));
    }
}
