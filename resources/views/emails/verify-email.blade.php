<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - ChicChevron Beauty</title>
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
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #218838;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
        .info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Verify Your Email Address</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $user->name }},</p>
        
        <p>Thank you for registering with ChicChevron Beauty! To complete your registration and start shopping, please verify your email address by clicking the button below:</p>
        
        <center>
            <a href="{{ $verificationUrl }}" class="button">
                Verify Email Address
            </a>
        </center>
        
        <div class="info">
            <strong>Why verify?</strong> Email verification helps us ensure that we can communicate with you about your orders, send important updates, and keep your account secure.
        </div>
        
        <p>Once verified, you'll have full access to:</p>
        <ul>
            <li>Place orders and track shipments</li>
            <li>Save items to your wishlist</li>
            <li>Write product reviews</li>
            <li>Receive exclusive offers and updates</li>
        </ul>
        
        <p>If you did not create an account with ChicChevron Beauty, please ignore this email.</p>
        
        <p>Happy shopping!<br>
        The ChicChevron Beauty Team</p>
    </div>
    
    <div class="footer">
        <p>If you're having trouble clicking the "Verify Email Address" button, copy and paste this URL into your web browser:</p>
        <p style="word-break: break-all; font-size: 0.8em;">{{ $verificationUrl }}</p>
        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e9ecef;">
        <p>&copy; {{ date('Y') }} ChicChevron Beauty. All rights reserved.</p>
        <p>This email was sent to {{ $user->email }}</p>
    </div>
</body>
</html>