<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Account Credentials</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .credentials-box { background-color: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .markets-list { background-color: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 3px; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to {{ config('app.name') }}</h1>
            <p>Your Sales Account Credentials</p>
        </div>

        <div class="content">
            <p>Hello {{ $sale->name }},</p>

            <p>Welcome to the {{ config('app.name') }} team! Your sales account has been created. Here are your login credentials:</p>

            <div class="credentials-box">
                <strong>Email:</strong> {{ $sale->email }}<br>
                <strong>Password:</strong> {{ $password }}<br>
                <strong>Phone:</strong> {{ $sale->phone }}<br>
                <strong>Address:</strong> {{ $sale->address }}
            </div>

            @if($sale->markets->count() > 0)
            <p><strong>Assigned Markets:</strong></p>
            <div class="markets-list">
                @foreach($sale->markets as $market)
                • {{ $market->name }} ({{ $market->address }})<br>
                @endforeach
            </div>
            @endif

            <p>You can log in at: <a href="{{ url('/admin') }}">{{ url('/admin') }}</a></p>

            <p><strong>Important:</strong> Please change your password after first login for security.</p>

            <p>As a sales representative, you will be responsible for conducting market surveys in your assigned areas.</p>

            <p>If you have any questions, please contact your administrator.</p>

            <p>Welcome aboard!</p>

            <p>Best regards,<br>
            {{ config('app.name') }} Team</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
