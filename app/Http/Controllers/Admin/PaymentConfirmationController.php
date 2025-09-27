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

        return view('admin.payment-confirmation.index', compact(
            'trip', 
            'pendingPayments', 
            'confirmedPayments',
            'tripStats'
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

        return view('admin.payment-confirmation.show', compact('payment'));
    }

    /**
     * Confirm individual payment
     */
    public function confirm(Request $request, Payment $payment)
    {
        $request->validate([
            'reference_code' => 'required|string|max:50|unique:payments,reference_code,' . $payment->id,
            'notes' => 'nullable|string|max:1000',
            'confirm_payment' => 'required|accepted',
        ]);

        try {
            // Update payment confirmation
            $payment->update([
                'reference_code' => $request->reference_code,
                'paid' => true,
                'updated_at' => now(),
            ]);

            // Send email notification
            $this->sendPaymentConfirmationEmail($payment, $request->notes);

            Log::info('Payment confirmed', [
                'payment_id' => $payment->id,
                'trip_id' => $payment->trip_id,
                'user_id' => $payment->user_id,
                'type' => $payment->type,
                'amount' => $payment->amount,
                'reference_code' => $request->reference_code,
                'confirmed_by' => Auth::id(),
            ]);

            return redirect()
                ->route('admin.payment-confirmation.index', $payment->trip)
                ->with('success', 'Payment confirmed successfully and email notification sent.');

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
}