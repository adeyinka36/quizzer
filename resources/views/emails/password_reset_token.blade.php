<div>
    <!-- Nothing worth having comes easy. - Theodore Roosevelt -->
</div>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px 30px;
            border: 1px solid #dddddd;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #3490dc;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
        }
        .token {
            display: block;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
            margin: 20px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px dashed #ccc;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 20px 0;
            background-color: #3490dc;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        }
        .footer {
            font-size: 12px;
            color: #777777;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Password Reset Request</h1>
    </div>
    <div class="content">
        <p>Hi {{ $firstName }} {{ $lastName }},</p>
        <p>We received a request to reset your password. If you initiated this request, please use the token below to reset your password:</p>
        <span class="token">{{ $token }}</span>
        <p>To reset your password:</p>
        <ol>
            <li>Click the button below or visit our password reset page.</li>
            <li>Enter your email address, the reset token above, and your new password.</li>
            <li>Submit the form to update your password.</li>
        </ol>
        <p style="text-align: center;">
            <a href="{{ url('/reset-password') }}" class="button">Reset Password</a>
        </p>
        <p>If you did not request a password reset, please ignore this email.</p>
        <p>Thank you,<br>The Ziva Team</p>
    </div>
    <div class="footer">
        <p>If you're having trouble, please contact our support team.</p>
    </div>
</div>
</body>
</html>
