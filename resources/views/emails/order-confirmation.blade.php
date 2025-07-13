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
        .content {
            padding: 30px;
        }
        .success-message {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        .success-icon {
            font-size: 48px;
            text-align: center;
            margin-bottom: 10px;
        }
        .order-info {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .order-items {
            margin: 20px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item-details h4 {
            margin: 0 0 5px 0;
            color: #111827;
        }
        .item-details p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }
        .totals {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .total-row.final {
            font-weight: 600;
            font-size: 18px;
            color: #111827;
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
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🌸 ChicChevron Beauty</h1>
            <p style="margin-top: 10px; opacity: 0.9;">Thank you for your order!</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Success Message -->
            <div class="success-message">
                <div class="success-icon">✅</div>
                <h2 style="text-align: center; margin: 0 0 10px 0; color: #059669;">Order Confirmed!</h2>
                <p style="text-align: center; margin: 0; color: #047857;">
                    Your order has been successfully placed and is being processed.
                </p>
            </div>

            <!-- Greeting -->
            <p>Hi {{ $customer['name'] }},</p>
            <p>Thank you for shopping with ChicChevron Beauty! We're excited to prepare your order.</p>

            <!-- Order Information -->
            <div class="order-info">
                <h3 style="margin-top: 0;">📦 Order Details</h3>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                <p><strong>Payment Method:</strong> {{ $payment['method'] }}</p>
                @if($payment['method'] === 'COD')
                    <p style="color: #dc2626;"><strong>Payment Due:</strong> LKR {{ number_format($order->total_amount, 2) }} (on delivery)</p>
                @else
                    <p><strong>Payment Status:</strong> {{ $payment['status'] }}</p>
                @endif
            </div>

            <!-- Order Items -->
            <div class="order-items">
                <h3>Items Ordered</h3>
                @foreach($items as $item)
                    <div class="item">
                        <div class="item-details">
                            <h4>{{ $item->product_name }}</h4>
                            @if($item->variant_details)
                                @php $variants = json_decode($item->variant_details, true); @endphp
                                <p>
                                    @if(isset($variants['size'])) Size: {{ $variants['size'] }} @endif
                                    @if(isset($variants['color'])) | Color: {{ $variants['color'] }} @endif
                                    @if(isset($variants['scent'])) | Scent: {{ $variants['scent'] }} @endif
                                </p>
                            @endif
                            <p>Qty: {{ $item->quantity }} × LKR {{ number_format($item->unit_price, 2) }}</p>
                        </div>
                        <div class="item-price">
                            <strong>LKR {{ number_format($item->total_price, 2) }}</strong>
                        </div>
                    </div>
                @endforeach

                <!-- Order Totals -->
                <div class="totals">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>LKR {{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="total-row">
                            <span>Discount:</span>
                            <span>- LKR {{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    @if($order->shipping_amount > 0)
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span>LKR {{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="total-row final">
                        <span>Total Amount:</span>
                        <span>LKR {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="order-info">
                <h3>🚚 Delivery Information</h3>
                <p><strong>Name:</strong> {{ $order->shipping_name }}</p>
                <p><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                <p><strong>Address:</strong><br>
                    {{ $order->shipping_address_line_1 }}<br>
                    @if($order->shipping_address_line_2)
                        {{ $order->shipping_address_line_2 }}<br>
                    @endif
                    {{ $order->shipping_city }}, {{ $order->shipping_district }}<br>
                    @if($order->shipping_postal_code)
                        Postal Code: {{ $order->shipping_postal_code }}
                    @endif
                </p>
                @if($order->notes)
                    <p><strong>Delivery Notes:</strong> {{ $order->notes }}</p>
                @endif
            </div>

            <!-- What's Next -->
            <div style="margin: 30px 0;">
                <h3>What happens next?</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="padding: 8px 0;">✓ We'll process your order within 1-2 business days</li>
                    <li style="padding: 8px 0;">✓ You'll receive an email when your order ships</li>
                    <li style="padding: 8px 0;">✓ Estimated delivery: 3-5 business days</li>
                </ul>
            </div>

            <!-- CTA Buttons -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $tracking['order_url'] }}" class="button">View Order Status</a>
            </div>

            <!-- Support Info -->
            <div style="text-align: center; margin-top: 30px;">
                <p style="color: #6b7280;">Questions about your order?</p>
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