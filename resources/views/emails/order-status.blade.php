<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $statusInfo['title'] }} - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 40px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .content {
            padding: 30px;
        }
        .status-update {
            background-color: #f8fafc;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            border-left: 4px solid {{ $statusInfo['color'] }};
        }
        .status-icon {
            font-size: 64px;
            margin-bottom: 15px;
        }
        .status-title {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 10px;
        }
        .order-info {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .order-detail {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .order-detail .label {
            color: #6b7280;
            font-weight: 500;
        }
        .order-detail .value {
            color: #111827;
            font-weight: 600;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 5px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        @media (max-width: 600px) {
            .order-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🌸 ChicChevron Beauty</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Status Update Section -->
            <div class="status-update">
                <div class="status-icon">{{ $statusInfo['icon'] }}</div>
                <div class="status-title">{{ $statusInfo['title'] }}</div>
                <div class="status-message">{{ $statusInfo['message'] }}</div>
                <div class="status-description">{{ $statusInfo['description'] }}</div>
            </div>

            <!-- Greeting -->
            <p>Hi {{ $customer['name'] }},</p>
            <p>We wanted to update you on your recent order with ChicChevron Beauty.</p>

            <!-- Order Information -->
            <div class="order-info">
                <h3>📦 Order Information</h3>
                <div class="order-details">
                    <div class="order-detail">
                        <span class="label">Order Number:</span>
                        <span class="value">{{ $order->order_number }}</span>
                    </div>
                    <div class="order-detail">
                        <span class="label">Order Date:</span>
                        <span class="value">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="order-detail">
                        <span class="label">Total Amount:</span>
                        <span class="value">LKR {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="order-detail">
                        <span class="label">Items:</span>
                        <span class="value">{{ $order->items->count() }} items</span>
                    </div>
                </div>
            </div>

            <!-- Comment Section (if comment exists) -->
            @if($comment)
                <div style="background-color: #fefce8; padding: 20px; border-radius: 8px; border-left: 4px solid #eab308; margin-bottom: 30px;">
                    <h4 style="margin: 0 0 10px 0; color: #92400e;">💬 Additional Information</h4>
                    <p style="margin: 0; color: #78716c; font-style: italic;">"{{ $comment }}"</p>
                    @if($adminName)
                        <p style="font-size: 12px; margin-top: 10px;">- {{ $adminName }}</p>
                    @endif
                </div>
            @endif

            <!-- Next Steps -->
            <div style="margin-bottom: 30px;">
                <h3>What's Next?</h3>
                <ul style="list-style: none; padding: 0;">
                    @foreach($nextSteps as $step)
                        <li style="background-color: #f0fdf4; padding: 12px 16px; margin-bottom: 8px; border-radius: 8px; border-left: 3px solid #10b981;">
                            ✓ {{ $step }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- CTA Buttons -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $tracking['order_url'] }}" class="button">View Order Details</a>
            </div>

            <!-- Support Info -->
            <div style="text-align: center; margin-top: 30px;">
                <p style="color: #6b7280;">Need help? Contact us:</p>
                <p style="color: #6b7280;">
                    📧 {{ $tracking['support_email'] }}<br>
                    📞 {{ $tracking['support_phone'] }}
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 5px 0; color: #6b7280; font-size: 14px;">
                Thank you for choosing ChicChevron Beauty!
            </p>
            <p style="margin: 5px 0; color: #6b7280; font-size: 12px;">
                © {{ date('Y') }} ChicChevron Beauty. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>