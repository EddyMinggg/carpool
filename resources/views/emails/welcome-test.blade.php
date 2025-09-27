<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to {{ $appName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        .content {
            color: #333;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🚗 {{ $appName }}</div>
            <h1>Welcome Test Email</h1>
        </div>

        <div class="content">
            <h2>Hello {{ $userName }}! 👋</h2>
            
            <p>This is a <strong>test welcome email</strong> from your Carpool Platform!</p>
            
            <p>🎉 <strong>Email sending is working correctly!</strong></p>
            
            <p>Here are some test features:</p>
            <ul>
                <li>✅ SMTP configuration is correct</li>
                <li>✅ Email templates are rendering properly</li>
                <li>✅ HTML formatting is working</li>
                <li>✅ Ready for production use!</li>
            </ul>

            <p>If you're seeing this email, your mail configuration is perfect!</p>

            <div style="text-align: center;">
                <a href="{{ $appUrl }}" class="button">Visit Platform 🚀</a>
            </div>

            <h3>📋 Test Information:</h3>
            <ul>
                <li><strong>Sent at:</strong> {{ now()->format('Y-m-d H:i:s') }}</li>
                <li><strong>Mail Driver:</strong> {{ config('mail.default') }}</li>
                <li><strong>From:</strong> {{ config('mail.from.address') }}</li>
                <li><strong>App URL:</strong> {{ $appUrl }}</li>
            </ul>
        </div>

        <div class="footer">
            <p>This is a test email from {{ $appName }}</p>
            <p>📧 Sent via {{ config('mail.default') }} mailer</p>
            <p>🌐 <a href="{{ $appUrl }}">{{ $appUrl }}</a></p>
        </div>
    </div>
</body>
</html>