<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Password Reset</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .password-box { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .warning { color: #856404; font-weight: bold; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Password Reset</h1>
        </div>

        <div class="content">
            <p>Hello {{ $admin->name }},</p>

            <p>Your admin password has been reset for security purposes. Here are your new login credentials:</p>

            <div class="password-box">
                <strong>Email:</strong> {{ $admin->email }}<br>
                <strong>New Password:</strong> {{ $newPassword }}
            </div>

            <p class="warning">⚠️ Please change this password immediately after logging in for security.</p>

            <p>You can log in at: <a href="{{ url('/admin') }}">{{ url('/admin') }}</a></p>

            <p>If you did not request this password reset, please contact your system administrator immediately.</p>

            <p>Best regards,<br>
            {{ config('app.name') }} Team</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
