<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice_number }} - {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 20mm;
            size: A4;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            border-bottom: 3px solid #667eea;
            margin-bottom: 20px;
            padding-bottom: 20px;
        }
        
        .company-info {
            float: left;
            width: 50%;
        }
        
        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .company-tagline {
            font-size: 12px;
            color: #666;
            font-style: italic;
            margin-bottom: 10px;
        }
        
        .company-details {
            font-size: 10px;
            color: #666;
            line-height: 1.3;
        }
        
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 14px;
            color: #667eea;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .invoice-dates {
            font-size: 10px;
            color: #666;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .customer-section {
            margin-bottom: 30px;
        }
        
        .bill-to {
            float: left;
            width: 48%;
        }
        
        .ship-to {
            float: right;
            width: 48%;
        }
        
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .customer-details {
            font-size: 10px;
            line-height: 1.4;
        }
        
        .customer-name {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            color: #495057;
        }
        
        .items-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            font-size: 10px;
            vertical-align: top;
        }
        
        .items-table .item-description {
            width: 40%;
        }
        
        .items-table .item-sku {
            width: 15%;
            font-family: monospace;
            font-size: 9px;
        }
        
        .items-table .item-qty {
            width: 10%;
            text-align: center;
        }
        
        .items-table .item-price {
            width: 15%;
            text-align: right;
        }
        
        .items-table .item-total {
            width: 20%;
            text-align: right;
            font-weight: bold;
        }
        
        .item-name {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .item-variants {
            font-size: 9px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .item-brand {
            font-size: 8px;
            color: #999;
        }
        
        .variant-badge {
            background-color: #e9ecef;
            color: #495057;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
            margin-right: 3px;
        }
        
        .totals-section {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 5px 10px;
            border-bottom: 1px solid #eee;
        }
        
        .totals-table .label {
            text-align: left;
            color: #666;
            font-size: 10px;
        }
        
        .totals-table .amount {
            text-align: right;
            font-weight: bold;
            width: 100px;
        }
        
        .totals-table .total-row {
            border-top: 2px solid #333;
            border-bottom: 3px double #333;
            background-color: #f8f9fa;
        }
        
        .totals-table .total-row td {
            font-size: 12px;
            font-weight: bold;
            padding: 8px 10px;
        }
        
        .payment-info {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .payment-section {
            float: left;
            width: 48%;
        }
        
        .notes-section {
            float: right;
            width: 48%;
        }
        
        .section-content {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            font-size: 10px;
        }
        
        .payment-method {
            font-weight: bold;
            color: #28a745;
            margin-bottom: 5px;
        }
        
        .payment-status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .payment-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .payment-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .footer {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #667eea;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .footer-company {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .footer-contact {
            margin-bottom: 3px;
        }
        
        .terms-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        
        .terms-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }
        
        .terms-list {
            font-size: 9px;
            color: #666;
            line-height: 1.3;
        }
        
        .terms-list li {
            margin-bottom: 3px;
        }
        
        /* Utility classes */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .text-muted { color: #666; }
        .mb-5 { margin-bottom: 5px; }
        .mb-10 { margin-bottom: 10px; }
        .mt-10 { margin-top: 10px; }
        
        /* Print-specific styles */
        @media print {
            body { margin: 0; }
            .page-break { page-break-before: always; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header clearfix">
        <div class="company-info">
            <div class="company-name">🌸 ChicChevron Beauty</div>
            <div class="company-tagline">Premium Beauty Products & Cosmetics</div>
            <div class="company-details">
                <strong>{{ $company['name'] }}</strong><br>
                {{ $company['address'] }}<br>
                {{ $company['city'] }}, {{ $company['postal_code'] }}<br>
                {{ $company['country'] }}<br><br>
                📞 {{ $company['phone'] }}<br>
                📧 {{ $company['email'] }}<br>
                🌐 {{ $company['website'] }}
                @if($company['registration_no'])
                    <br><br>Business Reg: {{ $company['registration_no'] }}
                @endif
                @if($company['tax_no'])
                    <br>Tax ID: {{ $company['tax_no'] }}
                @endif
            </div>
        </div>
        
        <div class="invoice-info">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number"># {{ $invoice_number }}</div>
            <div class="invoice-dates">
                <strong>Invoice Date:</strong> {{ $invoice_date }}<br>
                <strong>Due Date:</strong> {{ $due_date }}<br>
                <strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d') }}<br>
                <strong>Order #:</strong> {{ $order->order_number }}
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="customer-section clearfix">
        <div class="bill-to">
            <div class="section-title">BILL TO</div>
            <div class="customer-details">
                <div class="customer-name">{{ $customer['name'] }}</div>
                📧 {{ $customer['email'] }}<br>
                📞 {{ $customer['phone'] }}
            </div>
        </div>
        
        <div class="ship-to">
            <div class="section-title">SHIP TO</div>
            <div class="customer-details">
                <div class="customer-name">{{ $customer['name'] }}</div>
                📞 {{ $customer['phone'] }}<br>
                📍 {{ $customer['address'] }}
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th class="item-description">Description</th>
                <th class="item-sku">SKU</th>
                <th class="item-qty">Qty</th>
                <th class="item-price">Unit Price</th>
                <th class="item-price">Discount</th>
                <th class="item-total">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td class="item-description">
                        <div class="item-name">{{ $item['description'] }}</div>
                        @if($item['brand'] !== 'N/A')
                            <div class="item-brand">Brand: {{ $item['brand'] }}</div>
                        @endif
                    </td>
                    <td class="item-sku">{{ $item['sku'] }}</td>
                    <td class="item-qty text-center">{{ $item['quantity'] }}</td>
                    <td class="item-price text-right">{{ $calculations['currency'] }} {{ number_format($item['unit_price'], 2) }}</td>
                    <td class="item-price text-right">
                        @if($item['discount'] > 0)
                            -{{ $calculations['currency'] }} {{ number_format($item['discount'], 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="item-total text-right">{{ $calculations['currency'] }} {{ number_format($item['line_total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="amount text-right">{{ $calculations['currency'] }} {{ number_format($calculations['subtotal'], 2) }}</td>
            </tr>
            @if($calculations['discount'] > 0)
                <tr>
                    <td class="label">Discount:</td>
                    <td class="amount text-right">-{{ $calculations['currency'] }} {{ number_format($calculations['discount'], 2) }}</td>
                </tr>
            @endif
            @if($calculations['shipping'] > 0)
                <tr>
                    <td class="label">Shipping:</td>
                    <td class="amount text-right">{{ $calculations['currency'] }} {{ number_format($calculations['shipping'], 2) }}</td>
                </tr>
            @else
                <tr>
                    <td class="label">Shipping:</td>
                    <td class="amount text-right">FREE</td>
                </tr>
            @endif
            @if($calculations['tax_amount'] > 0)
                <tr>
                    <td class="label">Tax ({{ $calculations['tax_rate'] }}%):</td>
                    <td class="amount text-right">{{ $calculations['currency'] }} {{ number_format($calculations['tax_amount'], 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="label">TOTAL:</td>
                <td class="amount text-right">{{ $calculations['currency'] }} {{ number_format($calculations['total'], 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Payment Information -->
    <div class="payment-info clearfix">
        <div class="payment-section">
            <div class="section-title">PAYMENT INFORMATION</div>
            <div class="section-content">
                <div class="payment-method">{{ $payment_info['method'] }}</div>
                <div>Status: 
                    <span class="payment-status {{ strtolower($payment_info['status']) === 'completed' ? 'payment-completed' : 'payment-pending' }}">
                        {{ $payment_info['status'] }}
                    </span>
                </div>
                @if($payment_info['reference'])
                    <div class="mt-10">Reference: <strong>{{ $payment_info['reference'] }}</strong></div>
                @endif
            </div>
        </div>
        
        <div class="notes-section">
            <div class="section-title">NOTES</div>
            <div class="section-content">
                @if(count($notes) > 0)
                    @foreach($notes as $note)
                        <div class="mb-5">{{ $note }}</div>
                    @endforeach
                @else
                    <div class="text-muted">Thank you for your business!</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Terms and Conditions -->
    <div class="terms-section">
        <div class="terms-title">TERMS & CONDITIONS</div>
        <ul class="terms-list">
            @foreach($terms as $term)
                <li>{{ $term }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-company">{{ $company['name'] }}</div>
        <div class="footer-contact">{{ $company['phone'] }} | {{ $company['email'] }} | {{ $company['website'] }}</div>
        <div class="footer-contact">{{ $company['address'] }}, {{ $company['city'] }}, {{ $company['country'] }}</div>
        <div style="margin-top: 10px; font-size: 8px;">
            This is a computer-generated invoice. No signature required.
        </div>
        <div style="margin-top: 5px; font-size: 8px;">
            Generated on {{ now()->format('Y-m-d H:i:s') }} | Invoice {{ $invoice_number }}
        </div>
    </div>
</body>
</html>