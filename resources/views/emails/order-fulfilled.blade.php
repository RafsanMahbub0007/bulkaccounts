<!DOCTYPE html>
<html>
<head>
    <title>Your Order is Ready</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-w-600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #ef4444; text-align: center;">Order Completed!</h2>
        
        <p>Hello,</p>
        
        <p>Thank you for your order. Your payment has been confirmed, and your accounts are ready.</p>
        
        <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Total Amount:</strong> ${{ number_format($order->total_price, 2) }}</p>
            <p><strong>Date:</strong> {{ $order->completed_at->format('d M Y, h:i A') }}</p>
        </div>

        <p>We have attached an Excel file containing your purchased accounts to this email.</p>
        
        <p style="margin-top: 30px;">
            Need help? <a href="{{ route('contact') }}" style="color: #ef4444;">Contact Support</a>
        </p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #888; text-align: center;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
