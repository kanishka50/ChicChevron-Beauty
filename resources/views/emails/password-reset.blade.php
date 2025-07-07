<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - ChicChevron Beauty</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            padding: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #c82333;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Password Reset Request</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $user->name }},</p>
        
        <p>You are receiving this email because we received a password reset request for your account at ChicChevron Beauty.</p>
        
        <center>
            <a href="{{ $resetUrl }}" class="button">
                Reset Password
            </a>
        </center>
        
        <div class="warning">
            <strong>Important:</strong> This password reset link will expire in 60 minutes.
        </div>
        
        <p>If you did not request a password reset, no further action is required. Your password will remain unchanged.</p>
        
        <p>For security reasons, please do not share this email with anyone.</p>
        
        <p>Best regards,<br>
        The ChicChevron Beauty Team</p>
    </div>
    
    <div class="footer">
        <p>If you're having trouble clicking the "Reset Password" button, copy and paste this URL into your web browser:</p>
        <p style="word-break: break-all; font-size: 0.8em;">{{ $resetUrl }}</p>
        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e9ecef;">
        <p>&copy; {{ date('Y') }} ChicChevron Beauty. All rights reserved.</p>
    </div>
</body>
</html>