<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 0px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            /* Slightly smaller font for better fit */
            margin: 0px;
            padding: 0px;
            color: #333;
        }

        .header-bg {
            /* background-color: #f8f9fa; Removed */
            /* border-bottom: 2px solid #2C241B; Removed border */
            padding: 40px 40px 20px 40px;
        }

        .invoice-box {
            padding: 0 40px 40px 40px;
        }

        .header {
            display: table;
            width: 100%;
        }

        .logo-section {
            display: table-cell;
            vertical-align: top;
            width: 60%;
        }

        .invoice-details {
            display: table-cell;
            vertical-align: middle;
            /* Center vertically */
            text-align: right;
            width: 40%;
        }

        .logo {
            max-height: 45px;
            /* Smaller logo as requested */
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #2C241B;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 11px;
            color: #555;
            line-height: 1.4;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2C241B;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .invoice-meta {
            font-size: 12px;
            color: #555;
            line-height: 1.6;
            margin-top: 10px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2C241B;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
            margin-top: 30px;
            text-transform: uppercase;
        }

        .billing-info {
            width: 100%;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: transparent;
            /* Removed background */
            color: #333;
            /* Dark text instead of white */
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            padding: 8px 12px;
            text-align: left;
            border-bottom: 2px solid #333;
            /* Stronger border for separation */
        }

        td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .product-img {
            max-width: 40px;
            max-height: 40px;
            border-radius: 2px;
            object-fit: cover;
            vertical-align: middle;
            margin-right: 10px;
            border: 1px solid #eee;
        }

        .total-section {
            width: 100%;
            margin-top: 20px;
        }

        .total-table {
            width: 50%;
            float: right;
        }

        .total-row td {
            font-weight: bold;
            font-size: 12px;
            color: #333;
            border-top: none;
            padding: 5px 12px;
        }

        .grand-total td {
            font-size: 16px;
            color: #2C241B;
            /* Theme color text */
            padding: 10px 12px;
            border-top: 2px solid #2C241B;
            border-bottom: 2px solid #2C241B;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 40px;
            background-color: #fff;
            text-align: center;
            line-height: 40px;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #eee;
        }

        .status-badge {
            color: #28a745;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="header-bg">
        <div class="header">
            <div class="logo-section">
                <img src="{{ public_path('images/docor.logonew.jpeg') }}" class="logo" alt="Wooden Oak">
                <div class="company-name">Wooden Oak Studio</div>
                <div class="company-address">
                    123 Furniture Lane, Wood City, WC 12345<br>
                    Email: support@woodenoak.com &bull; Phone: +91 98765 43210
                </div>
            </div>
            <div class="invoice-details">
                <div class="invoice-title">Invoice</div>
                <div class="invoice-meta">
                    #{{ $order->order_number }}<br>
                    {{ $order->created_at->format('F d, Y') }}<br>
                    <span class="status-badge">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice-box">
        <div class="billing-info">
            <div class="section-title">Bill To</div>
            <div style="font-size: 13px; line-height: 1.5;">
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $order->customer_email }}<br>
                {{ $order->customer_phone }}<br>
                <div style="margin-top: 5px; color: #555;">
                    {{ $order->customer_address }}
                </div>
            </div>
        </div>

        <div class="section-title">Order Items</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Product</th>
                    <th class="text-right" style="width: 15%;">Price</th>
                    <th class="text-center" style="width: 10%;">Qty</th>
                    <th class="text-right" style="width: 25%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            {{-- Image Handling logic --}}
                            @php
                                $imagePath = null;
                                if ($item->product && $item->product->image) {
                                    $image = $item->product->image;
                                    if (Str::startsWith($image, 'http')) {
                                        $imagePath = $image;
                                    } else {
                                        $possiblePath = public_path('storage/' . $image);
                                        if (file_exists($possiblePath)) {
                                            $imagePath = $possiblePath;
                                        }
                                    }
                                }
                            @endphp

                            @if($imagePath)
                                <img src="{{ $imagePath }}" class="product-img">
                            @endif

                            <span
                                style="font-weight: 500;">{{ $item->product ? $item->product->name : 'Product Deleted' }}</span>
                        </td>
                        <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table class="total-table">
                <tr class="total-row">
                    <td class="text-right">Subtotal</td>
                    <td class="text-right">₹{{ number_format($order->total_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td class="text-right">Shipping</td>
                    <td class="text-right">₹0.00</td>
                </tr>
                <tr class="grand-total">
                    <td class="text-right">Total Amount</td>
                    <td class="text-right">₹{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
            <div style="clear: both;"></div>
        </div>
    </div>

    <div class="footer">
        Thank you for shopping with Wooden Oak Studio!
    </div>
</body>

</html>