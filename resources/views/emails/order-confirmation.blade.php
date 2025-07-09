<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - {{ $order->order_number }}</title>
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
        .success-message {
            background-color: #d1fae5;
            color: #047857;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
            border-left: 4px solid #10b981;
        }
        .success-message .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .order-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            margin-right: 15px;
            object-fit: cover;
        }
        .item-details {
            flex: 1;
        }
        .item-name {
            font-weight: 600;
            color: #111827;
            margin-bottom: 5px;
        }
        .item-variants {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .item-brand {
            font-size: 12px;
            color: #9ca3af;
        }
        .item-price {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }
        .item-quantity {
            font-size: 14px;
            color: #6b7280;
        }
        .variant-badge {
            display: inline-block;
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-right: 5px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .totals-table td {
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .totals-table .label {
            color: #6b7280;
            width: 70%;
        }
        .totals-table .amount {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }
        .totals-table .total-row {
            border-top: 2px solid #e5e7eb;
            font-size: 18px;
            font-weight: bold;
        }
        .totals-table .total-row td {
            padding-top: 15px;
            border-bottom: none;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .info-card {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
        }
        .info-card h4 {
            margin: 0 0 10px 0;
            color: #374151;
            font-size: 16px;
        }
        .info-card p {
            margin: 5px 0;
            color: #6b7280;
            font-size: 14px;
        }
        .payment-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .payment-completed {
            background-color: #d1fae5;
            color: #047857;
        }
        .payment-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px auto;
            display: block;
            width: fit-content;
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
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            margin: 0 10px;
            color: #6b7280;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .content {
                padding: 20px;
            }
            .order-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .item-price {
                text-align: left;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🌸 ChicChevron Beauty</h1>
            <div class="order-number">Order Confirmation</div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Success Message -->
            <div class="success-message">
                <div class="icon">✅</div>
                <h2 style="margin: 0;">Thank You for Your Order!</h2>
                <p style="margin: 10px 0 0 0;">Order {{ $order->order_number }} has been confirmed</p>
            </div>

            <!-- Greeting -->
            <p>Hi {{ $customer['name'] }},</p>
            <p>Thank you for shopping with ChicChevron Beauty! We're excited to prepare your beautiful selection of products. Your order has been confirmed and will be processed shortly.</p>

            <!-- Order Items -->
            <div class="section">
                <h3 class="section-title">Order Items ({{ $items->count() }})</h3>
                @foreach($items as $item)
                    <div class="order-item">
                        <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                             alt="{{ $item->product_name }}" 
                             class="item-image">
                        <div class="item-details">
                            <div class="item-name">{{ $item->product_name }}</div>
                            @if($item->variantCombination)
                                @php $variantDetails = json_decode($item->variant_details, true); @endphp
                                <div class="item-variants">
                                    @if(!empty($variantDetails['size']))
                                        <span class="variant-badge">{{ $variantDetails['size'] }}</span>
                                    @endif
                                    @if(!empty($variantDetails['color']))
                                        <span class="variant-badge">{{ $variantDetails['color'] }}</span>
                                    @endif
                                    @if(!empty($variantDetails['scent']))
                                        <span class="variant-badge">{{ $variantDetails['scent'] }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="item-brand">{{ $item->product->brand->name ?? 'ChicChevron Beauty' }}</div>
                        </div>
                        <div class="item-price">
                            <div>LKR {{ number_format($item->unit_price, 2) }}</div>
                            <div class="item-quantity">Qty: {{ $item->quantity }}</div>
                            @if($item->discount_amount > 0)
                                <div style="color: #10b981; font-size: 12px;">
                                    -LKR {{ number_format($item->discount_amount, 2) }}
                                </div>
                            @endif
                            <div style="font-weight: bold; margin-top: 5px;">
                                LKR {{ number_format($item->total_price, 2) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Totals -->
            <div class="section">
                <h3 class="section-title">Order Summary</h3>
                <table class="totals-table">
                    <tr>
                        <td class="label">Subtotal:</td>
                        <td class="amount">LKR {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                        <tr>
                            <td class="label">Discount:</td>
                            <td class="amount" style="color: #10b981;">-LKR {{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    @if($order->shipping_amount > 0)
                        <tr>
                            <td class="label">Shipping:</td>
                            <td class="amount">LKR {{ number_format($order->shipping_amount, 2) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="label">Shipping:</td>
                            <td class="amount" style="color: #10b981;">FREE</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td class="label">Total:</td>
                        <td class="amount">LKR {{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Customer & Payment Info -->
            <div class="info-grid">
                <div class="info-card">
                    <h4>🚚 Shipping Address</h4>
                    <p><strong>{{ $customer['name'] }}</strong></p>
                    <p>{{ $customer['phone'] }}</p>
                    <p>{{ $customer['address'] }}</p>
                </div>
                <div class="info-card">
                    <h4>💳 Payment Information</h4>
                    <p><strong>Method:</strong> {{ $payment['method'] }}</p>
                    <p><strong>Status:</strong> 
                        <span class="payment-badge {{ $payment['status'] === 'Completed' ? 'payment-completed' : 'payment-pending' }}">
                            {{ $payment['status'] }}
                        </span>
                    </p>
                    @if($payment['reference'])
                        <p><strong>Reference:</strong> {{ $payment['reference'] }}</p>
                    @endif
                </div>
            </div>

            <!-- Next Steps -->
            <div class="section">
                <h3 class="section-title">What Happens Next?</h3>
                <ul style="color: #6b7280; padding-left: 20px;">
                    <li style="margin-bottom: 8px;">📦 We'll start processing your order within 24 hours</li>
                    <li style="margin-bottom: 8px;">🚚 You'll receive a shipping notification with tracking details</li>
                    <li style="margin-bottom: 8px;">📱 Track your order anytime in your account</li>
                    <li style="margin-bottom: 8px;">📞 Contact us if you have any questions</li>
                </ul>
            </div>

            <!-- CTA Button -->
            <a href="{{ $tracking['order_url'] }}" class="cta-button">
                Track Your Order
            </a>

            <!-- Contact Info -->
            <div style="background-color: #f3f4f6; padding: 20px; border-radius: 8px; text-align: center;">
                <h4 style="margin: 0 0 10px 0; color: #374151;">Need Help?</h4>
                <p style="margin: 5px 0; color: #6b7280;">
                    📧 Email: <a href="mailto:{{ $tracking['support_email'] }}" style="color: #667eea;">{{ $tracking['support_email'] }}</a>
                </p>
                <p style="margin: 5px 0; color: #6b7280;">
                    📞 Phone: {{ $tracking['support_phone'] }}
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="company-name">ChicChevron Beauty</p>
            <p>Your trusted partner for premium beauty products</p>
            <p>Ratnapura, Sabaragamuwa Province, Sri Lanka</p>
            
            <div class="social-links">
                <a href="#" style="text-decoration: none;">📘 Facebook</a>
                <a href="#" style="text-decoration: none;">📷 Instagram</a>
                <a href="#" style="text-decoration: none;">🐦 Twitter</a>
            </div>
            
            <p style="margin-top: 20px; font-size: 12px;">
                This email was sent to {{ $customer['email'] }}. 
                <br>© {{ date('Y') }} ChicChevron Beauty. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>