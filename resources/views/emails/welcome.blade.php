<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ChicChevron Beauty</title>
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
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to ChicChevron Beauty!</h1>
    </div>
    
    <div class="content">
        <p>Hi {{ $user->name }},</p>
        
        <p>Thank you for joining ChicChevron Beauty! We're excited to have you as part of our beauty community.</p>
        
        <p>With your new account, you can:</p>
        <ul>
            <li>Shop from our wide range of skin care, hair care, and baby care products</li>
            <li>Track your orders and delivery status</li>
            <li>Save your favorite products to your wishlist</li>
            <li>Leave reviews and help other customers</li>
            <li>Enjoy exclusive member-only offers</li>
        </ul>
        
        @if(!$user->hasVerifiedEmail())
        <p>Please verify your email address to get started:</p>
        <center>
            <a href="{{ route('verification.verify', ['id' => $user->id, 'hash' => sha1($user->email)]) }}" class="button">
                Verify Email Address
            </a>
        </center>
        @endif
        
        <p>If you have any questions, feel free to contact us.</p>
        
        <p>Happy shopping!</p>
        <p>The ChicChevron Beauty Team</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} ChicChevron Beauty. All rights reserved.</p>
        <p>This email was sent to {{ $user->email }}</p>
    </div>
</body>
</html>