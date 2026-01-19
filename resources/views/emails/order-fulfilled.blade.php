<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        /* Reset */
        body, p, h1, h2, h3, h4, h5, h6 { margin: 0; padding: 0; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; -webkit-font-smoothing: antialiased; }
        
        /* Container */
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        
        /* Header */
        .header { background-color: #1a1a1a; padding: 30px 20px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: 1px; margin: 0; }
        
        /* Body */
        .body { padding: 40px 30px; }
        .greeting { font-size: 18px; margin-bottom: 20px; color: #111; }
        .message { margin-bottom: 30px; color: #555; }
        
        /* Order Info Box */
        .order-info { background-color: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 30px; border: 1px solid #e9ecef; }
        .order-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .order-row:last-child { margin-bottom: 0; }
        .label { color: #666; font-weight: 500; }
        .value { color: #111; font-weight: 600; }
        
        /* Table */
        .table-container { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .table-container th { text-align: left; padding: 12px; background-color: #f8f9fa; color: #666; font-size: 14px; font-weight: 600; border-bottom: 2px solid #eee; }
        .table-container td { padding: 12px; border-bottom: 1px solid #eee; color: #333; font-size: 14px; }
        .text-right { text-align: right; }
        .total-row td { font-weight: bold; font-size: 16px; border-top: 2px solid #eee; border-bottom: none; padding-top: 15px; color: #111; }
        
        /* Button */
        .btn-container { text-align: center; margin: 40px 0; }
        .btn { background-color: #ef4444; color: #ffffff !important; padding: 14px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; font-size: 16px; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3); }
        .btn:hover { background-color: #dc2626; }
        
        /* Footer */
        .footer { background-color: #f8f9fa; padding: 30px 20px; text-align: center; border-top: 1px solid #eee; }
        .footer p { color: #888; font-size: 13px; margin-bottom: 10px; }
        .footer a { color: #666; text-decoration: none; margin: 0 5px; }
        .footer a:hover { color: #ef4444; }
    </style>
</head>
<body>
    @php
        $system = \App\Models\Setting::find(1);
    @endphp
    <div style="padding: 20px 0;">
        <div class="container">
            <!-- Header -->
            <div class="header">
                @if($system && $system->logo)
                    <img src="{{ image_path($system->logo) }}" alt="{{ config('app.name') }}" style="max-width: 200px; max-height: 60px; border: 0; outline: none; text-decoration: none; display: block; margin: 0 auto;">
                @else
                    <h1>{{ config('app.name') }}</h1>
                @endif
            </div>

            <!-- Body -->
            <div class="body">
                <p class="greeting">Hello,</p>
                <p class="message">Thank you for your order! We are pleased to confirm that your payment has been received and your order is ready.</p>

                <!-- Order Details -->
                <div class="order-info">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="label" style="padding-bottom: 8px;">Order Number:</td>
                            <td class="value" style="text-align: right; padding-bottom: 8px;">{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="padding-bottom: 8px;">Date:</td>
                            <td class="value" style="text-align: right; padding-bottom: 8px;">{{ $order->completed_at ? $order->completed_at->format('M d, Y') : now()->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Status:</td>
                            <td class="value" style="text-align: right; color: #10b981;">Completed</td>
                        </tr>
                    </table>
                </div>

                <!-- Product Table -->
                <table class="table-container" width="100%">
                    <thead>
                        <tr>
                            <th width="50%">Product</th>
                            <th width="15%" class="text-right">Qty</th>
                            <th width="20%" class="text-right">Price</th>
                            <th width="15%" class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="3" class="text-right">Grand Total:</td>
                            <td class="text-right">${{ number_format($order->total_price, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Download Action -->
                @if($order->download_file)
                <div class="btn-container">
                    <a href="{{ route('order.download.public', ['order' => $order->order_number]) }}" class="btn">Download Accounts File</a>
                    <p style="margin-top: 15px; font-size: 13px; color: #666;">
                        Or check the attachment included with this email.
                    </p>
                </div>
                @endif

                <p style="font-size: 14px; color: #666; margin-top: 30px;">
                    If you have any issues accessing your account, please don't hesitate to contact our support team.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <div style="margin-top: 10px;">
                    <a href="{{ route('home') }}">Home</a> •
                    <a href="{{ route('contact') }}">Support</a> •
                    <a href="{{ route('privacy') }}">Privacy</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>