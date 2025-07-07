<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ChicChevron Beauty</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #dc2626; padding: 30px; text-align: center;">
        <h1 style="color: white; margin: 0;">ChicChevron Beauty</h1>
    </div>
    
    <div style="background-color: #f9fafb; padding: 30px;">
        <h2 style="color: #dc2626;">Welcome, {{ $user->name }}!</h2>
        
        <p>Thank you for joining ChicChevron Beauty. We're excited to have you as part of our community!</p>
        
        <p>At ChicChevron Beauty, we offer:</p>
        <ul>
            <li>Premium beauty products for skin care, hair care, and baby care</li>
            <li>100% authentic products from trusted brands</li>
            <li>Fast delivery across Sri Lanka</li>
            <li>Secure payment options including Cash on Delivery</li>
        </ul>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('products.index') }}" style="background-color: #dc2626; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Start Shopping</a>
        </div>
        
        <p>If you have any questions, feel free to contact us at info@chicchevronbeauty.com</p>
        
        <p>Best regards,<br>
        The ChicChevron Beauty Team</p>
    </div>
    
    <div style="background-color: #1f2937; color: #9ca3af; padding: 20px; text-align: center; font-size: 14px;">
        <p style="margin: 0;">© {{ date('Y') }} ChicChevron Beauty. All rights reserved.</p>
    </div>
</body>
</html>