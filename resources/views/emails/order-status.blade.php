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
        .header .order-number {
            font-size: 18px;
            margin-top: 10px;
            opacity: 0.9;
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
        .status-message {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 15px;
        }
        .status-description {
            font-size: 16px;
            color: #374151;
            line-height: 1.5;
        }
        .order-info {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .order-info h3 {
            margin: 0 0 15px 0;
            color: #374151;
            font-size: 18px;
        }
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .order-detail {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        .next-steps {
            margin-bottom: 30px;
        }
        .next-steps h3 {
            color: #374151;
            font-size: 20px;
            margin-bottom: 15px;
        }
        .steps-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .steps-list li {
            background-color: #f0fdf4;
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 8px;
            border-left: 3px solid #10b981;
            color: #374151;
        }
        .steps-list li:before {
            content: "✓";
            color: #10b981;
            font-weight: bold;
            margin-right: 10px;
        }
        .comment-section {
            background-color: #fefce8;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #eab308;
            margin-bottom: 30px;
        }
        .comment-section h4 {
            margin: 0 0 10px 0;
            color: #92400e;
            font-size: 16px;
        }
        .comment-section p {
            margin: 0;
            color: #78716c;
            font-style: italic;
        }
        .cta-section {
            text-align: center;
            margin: 30px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px;
        }
        .cta-button.secondary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .timeline {
            margin: 30px 0;
        }
        .timeline h3 {
            color: #374151;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
        }
        .timeline-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 14px;
            color: white;
        }
        .timeline-icon.completed {
            background-color: #10b981;
        }
        .timeline-icon.current {
            background-color: {{ $statusInfo['color'] }};
        }
        .timeline-icon.pending {
            background-color: #d1d5db;
        }
        .timeline-text {
            flex: 1;
            color: #374151;
        }
        .timeline-text.current {
            font-weight: 600;
            color: #111827;
        }
        .contact-info {
            background-color: #f3f4f6;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .contact-info h4 {
            margin: 0 0 15px 0;
            color: #374151;
        }
        .contact-info p {
            margin: 5px 0;
            color: #6b7280;
        }
        .contact-info a {
            color: #667eea;
            text-decoration: none;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
            color: #6b7280;
            font-size: 14px;
        }
        .footer .company-name {
            font-weight: 600;
            color: #374151;
        }
        @media (max-width: 600px) {
            .order-details {
                grid-template-columns: 1fr;
            }
            .content {
                padding: 20px;
            }
            .cta-button {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🌸 ChicChevron Beauty</h1>
            <div class="order-number">Order Update</div>
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
                <div class="comment-section">
                    <h4>💬 Additional Information</h4>
                    <p>"{{ $comment }}"</p>
                    @if($adminName)
                        <p style="font-size: 12px; margin-top: 10px;">- {{ $adminName }}</p>
                    @endif
                </div>
            @endif

            <!-- Order Timeline -->
            <div class="timeline">
                <h3>📋 Order Progress</h3>
                
                <div class="timeline-item">
                    <div class="timeline-icon completed">✓</div>
                    <div class="timeline-text">Order Confirmed & Payment Processed</div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-icon {{ in_array($newStatus, ['processing', 'shipping', 'completed']) ? 'completed' : ($newStatus === 'processing' ? 'current' : 'pending') }}">
                        {{ in_array($newStatus, ['processing', 'shipping', 'completed']) ? '✓' : '2' }}
                    </div>
                    <div class="timeline-text {{ $newStatus === 'processing' ? 'current' : '' }}">Order Processing</div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-icon {{ in_array($newStatus, ['shipping', 'completed']) ? 'completed' : ($newStatus === 'shipping' ? 'current' : 'pending') }}">
                        {{ in_array($newStatus, ['shipping', 'completed']) ? '✓' : '3' }}
                    </div>
                    <div class="timeline-text {{ $newStatus === 'shipping' ? 'current' : '' }}">Order Shipped</div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-icon {{ $newStatus === 'completed' ? 'completed current' : 'pending' }}">
                        {{ $newStatus === 'completed' ? '✓' : '4' }}
                    </div>
                    <div class="timeline-text {{ $newStatus === 'completed' ? 'current' : '' }}">Order Delivered</div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>🎯 What's Next?</h3>
                <ul class="steps-list">
                    @foreach($nextSteps as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ul>
            </div>

            <!-- CTA Buttons -->
            <div class="cta-section">
                <a href="{{ $tracking['order_url'] }}" class="cta-button">
                    View Order Details
                </a>
                
                @if($newStatus === 'completed')
                    <a href="{{ route('user.reviews.create', $order) }}" class="cta-button secondary">
                        Leave a Review
                    </a>
                @endif
            </div>

            <!-- Contact Info -->
            <div class="contact-info">
                <h4>📞 Need Help?</h4>
                <p>Our customer support team is here to help you!</p>
                <p>
                    📧 Email: <a href="mailto:{{ $tracking['support_email'] }}">{{ $tracking['support_email'] }}</a>
                </p>
                <p>
                    📞 Phone: {{ $tracking['support_phone'] }}
                </p>
                <p style="font-size: 12px; margin-top: 15px; color: #9ca3af;">
                    Support Hours: Monday - Friday, 9:00 AM - 6:00 PM (Sri Lanka Time)
                </p>
            </div>

            <!-- Thank You Message -->
            <div style="text-align: center; margin: 30px 0;">
                <p style="font-size: 18px; color: #374151;">
                    Thank you for choosing ChicChevron Beauty! 💖
                </p>
                <p style="color: #6b7280;">
                    We appreciate your business and hope you love your products.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="company-name">ChicChevron Beauty</p>
            <p>Your trusted partner for premium beauty products</p>
            <p>Ratnapura, Sabaragamuwa Province, Sri Lanka</p>
            
            <p style="margin-top: 20px; font-size: 12px;">
                This email was sent to {{ $customer['email'] }}. 
                <br>© {{ date('Y') }} ChicChevron Beauty. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>