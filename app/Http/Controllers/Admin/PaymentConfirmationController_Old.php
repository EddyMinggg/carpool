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
            ->orderBy('created_at', 'desc')
            ->get();

        // Get confirmed payments for reference
        $confirmedPayments = Payment::with(['trip', 'user'])
            ->where('trip_id', $trip->id)
            ->where('paid', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        // Calculate trip statistics
        $tripStats = [
            'total_members' => TripJoin::where('trip_id', $trip->id)->count(),
            'deposit_payments' => $pendingPayments->where('type', 'deposit')->count() + $confirmedPayments->where('type', 'deposit')->count(),
            'remaining_payments' => $pendingPayments->where('type', 'remaining')->count() + $confirmedPayments->where('type', 'remaining')->count(),
            'total_confirmed_amount' => $confirmedPayments->sum('amount'),
        ];

        return view('admin.payment-confirmation.index', compact(
            'trip', 
            'pendingPayments', 
            'confirmedPayments',
            'tripStats'
        ));
    }
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

        return view('admin.payment-confirmation.show', compact('payment'));
    }

    /**
     * Confirm payment with reference code
     */
    public function confirm(Request $request, TripJoin $tripJoin)
    {
        $request->validate([
            'reference_code' => 'required|string|min:3|max:50',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        try {
            // Check if payment is already confirmed
            if ($tripJoin->payment_confirmed) {
                return redirect()->back()
                    ->with('error', 'Payment already confirmed for this member.');
            }

            // Check if reference code already exists for this trip
            $existingCode = TripJoin::where('trip_id', $tripJoin->trip_id)
                ->where('reference_code', $request->reference_code)
                ->where('id', '!=', $tripJoin->id)
                ->first();

            if ($existingCode) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['reference_code' => 'This reference code has already been used for this trip.']);
            }

            // Update payment confirmation
            $tripJoin->update([
                'reference_code' => $request->reference_code,
                'payment_confirmed' => true,
                'payment_confirmed_at' => Carbon::now(),
                'confirmed_by' => Auth::id(),
            ]);

            // Send confirmation email
            $this->sendPaymentConfirmationEmail($tripJoin, $request->admin_notes);

            // Log the confirmation
            Log::info('Payment confirmed by admin', [
                'trip_join_id' => $tripJoin->id,
                'trip_id' => $tripJoin->trip_id,
                'user_id' => $tripJoin->user_id,
                'reference_code' => $request->reference_code,
                'confirmed_by' => Auth::id(),
                'confirmed_at' => Carbon::now(),
            ]);

            return redirect()->route('admin.payment-confirmation.index', $tripJoin->trip)
                ->with('success', "Payment confirmed for {$tripJoin->user->username}. Confirmation email sent.");

        } catch (\Exception $e) {
            Log::error('Payment confirmation failed', [
                'trip_join_id' => $tripJoin->id,
                'error' => $e->getMessage(),
                'admin_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to confirm payment. Please try again.');
        }
    }

    /**
     * Bulk confirm multiple payments
     */
    public function bulkConfirm(Request $request, Trip $trip)
    {
        $request->validate([
            'confirmations' => 'required|array|min:1',
            'confirmations.*.trip_join_id' => 'required|exists:trip_joins,id',
            'confirmations.*.reference_code' => 'required|string|min:3|max:50',
        ]);

        $confirmed = 0;
        $errors = [];

        foreach ($request->confirmations as $confirmation) {
            try {
                $tripJoin = TripJoin::findOrFail($confirmation['trip_join_id']);
                
                // Skip if already confirmed
                if ($tripJoin->payment_confirmed) {
                    continue;
                }

                // Check for duplicate reference codes
                $existingCode = TripJoin::where('trip_id', $trip->id)
                    ->where('reference_code', $confirmation['reference_code'])
                    ->first();

                if ($existingCode) {
                    $errors[] = "Reference code '{$confirmation['reference_code']}' already used.";
                    continue;
                }

                // Confirm payment
                $tripJoin->update([
                    'reference_code' => $confirmation['reference_code'],
                    'payment_confirmed' => true,
                    'payment_confirmed_at' => Carbon::now(),
                    'confirmed_by' => Auth::id(),
                ]);

                // Send email
                $this->sendPaymentConfirmationEmail($tripJoin);
                $confirmed++;

            } catch (\Exception $e) {
                $errors[] = "Failed to confirm payment for join ID {$confirmation['trip_join_id']}.";
                Log::error('Bulk payment confirmation error', [
                    'trip_join_id' => $confirmation['trip_join_id'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $message = "Confirmed {$confirmed} payment(s).";
        if (!empty($errors)) {
            $message .= ' Errors: ' . implode(' ', $errors);
        }

        return redirect()->route('admin.payment-confirmation.index', $trip)
            ->with($confirmed > 0 ? 'success' : 'error', $message);
    }

    /**
     * Send payment confirmation email
     */
    private function sendPaymentConfirmationEmail(TripJoin $tripJoin, string $adminNotes = null)
    {
        try {
            $user = $tripJoin->user;
            $trip = $tripJoin->trip;
            
            // Prepare email data
            $emailData = [
                'userName' => $user->name ?: $user->username,
                'userEmail' => $user->email,
                'tripId' => $trip->id,
                'destination' => $trip->dropoff_location,
                'departureDate' => $trip->planned_departure_time->format('Y-m-d'),
                'departureTime' => $trip->planned_departure_time->format('H:i'),
                'pickupLocation' => $tripJoin->pickup_location ?: 'To be determined',
                'amountPaid' => number_format((float)$tripJoin->user_fee, 2),
                'paymentType' => 'Deposit Payment',
                'referenceCode' => $tripJoin->reference_code,
                'confirmedDate' => $tripJoin->payment_confirmed_at->format('Y-m-d H:i'),
                'tripUrl' => route('trips.show', $trip->id),
                'appName' => config('app.name', 'Carpool Platform'),
                'appUrl' => config('app.url'),
                'adminNotes' => $adminNotes,
            ];

            // Send email
            Mail::send('emails.payment-confirmed', $emailData, function ($message) use ($user, $trip) {
                $message->to($user->email, $user->name ?: $user->username)
                        ->subject("Payment Confirmed - Trip to {$trip->dropoff_location}");
            });

            Log::info('Payment confirmation email sent', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'trip_id' => $trip->id,
                'reference_code' => $tripJoin->reference_code,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmation email', [
                'user_id' => $tripJoin->user_id,
                'trip_id' => $tripJoin->trip_id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Show payment statistics for admin dashboard
     */
    public function statistics()
    {
        $stats = [
            'pending_confirmations' => TripJoin::pending()->count(),
            'confirmed_today' => TripJoin::confirmed()
                ->whereDate('payment_confirmed_at', Carbon::today())
                ->count(),
            'confirmed_this_week' => TripJoin::confirmed()
                ->whereBetween('payment_confirmed_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])
                ->count(),
            'total_confirmed' => TripJoin::confirmed()->count(),
        ];

        // Recent confirmations
        $recentConfirmations = TripJoin::with(['user', 'trip', 'confirmedBy'])
            ->confirmed()
            ->orderBy('payment_confirmed_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.payment-confirmation.statistics', compact('stats', 'recentConfirmations'));
    }
}