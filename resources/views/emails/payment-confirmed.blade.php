<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Confirmed - {{ $appName }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f8fafc;
            color: #374151;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 30px;
        }
        .trip-info {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        .trip-info h3 {
            margin: 0 0 12px 0;
            color: #1f2937;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 6px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 500;
            color: #6b7280;
        }
        .info-value {
            font-weight: 600;
            color: #1f2937;
        }
        .status-badge {
            display: inline-block;
            background-color: #d1fae5;
            color: #065f46;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin: 16px 0;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }
        .next-steps {
            background-color: #fef3c7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .next-steps h4 {
            margin: 0 0 12px 0;
            color: #92400e;
            font-size: 16px;
        }
        .next-steps ul {
            margin: 0;
            padding-left: 20px;
            color: #78350f;
        }
        .next-steps li {
            margin-bottom: 6px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
            }
            .header, .content {
                padding: 20px;
            }
            .info-row {
                flex-direction: column;
                gap: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Payment Confirmed!</h1>
            <p>Welcome to your carpool trip</p>
        </div>

        <div class="content">
            <p>Hi <strong>{{ $userName }}</strong>,</p>
            
            <p>Great news! Your payment has been confirmed and you've successfully joined the carpool trip. Here are your trip details:</p>

            <div class="trip-info">
                <h3>🚗 Trip Information</h3>
                <div class="info-row">
                    <span class="info-label">Destination:</span>
                    <span class="info-value">{{ $destination }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Departure Date:</span>
                    <span class="info-value">{{ $departureDate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Departure Time:</span>
                    <span class="info-value">{{ $departureTime }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Your Pickup Location:</span>
                    <span class="info-value">{{ $pickupLocation }}</span>
                </div>
            </div>

            <div class="trip-info">
                <h3>💰 Payment Details</h3>
                <div class="info-row">
                    <span class="info-label">Amount Paid:</span>
                    <span class="info-value">HK$ {{ $amountPaid }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Type:</span>
                    <span class="info-value">{{ $paymentType }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Reference Code:</span>
                    <span class="info-value">{{ $referenceCode }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Confirmed Date:</span>
                    <span class="info-value">{{ $confirmedDate }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <span class="status-badge">✅ Payment Confirmed - Trip Joined</span>
            </div>

            <div class="next-steps">
                <h4>📋 What's Next?</h4>
                <ul>
                    <li><strong>Wait for departure:</strong> You'll receive updates as the departure time approaches</li>
                    <li><strong>Driver assignment:</strong> Driver details will be shared before departure</li>
                    <li><strong>Stay reachable:</strong> Keep your phone accessible for driver contact</li>
                    <li><strong>Be ready:</strong> Arrive at your pickup location on time</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ $tripUrl }}" class="action-button">
                    View Trip Details →
                </a>
            </div>

            <p style="margin-top: 30px;">If you have any questions or need to make changes to your booking, please don't hesitate to contact us.</p>
            
            <p>Safe travels!<br>
            <strong>{{ $appName }} Team</strong></p>
        </div>

        <div class="footer">
            <p>This email was sent regarding your carpool booking.</p>
            <p><a href="{{ $appUrl }}">{{ $appName }}</a> | <a href="mailto:{{ config('mail.from.address') }}">Contact Support</a></p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                Booking Reference: {{ $referenceCode }}
            </p>
        </div>
    </div>
</body>
</html>