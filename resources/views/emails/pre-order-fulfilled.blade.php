<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pre-Order Fulfilled</title>
    <style>
        body { font-family: sans-serif; background-color: #f9fafb; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { display: inline-block; background-color: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="color: #1f2937;">Good News! Your Pre-Order is Ready</h1>
        <p>Hi {{ $preOrder->name }},</p>
        <p>We are excited to inform you that your pre-ordered items are now available.</p>
        <p><strong>Order Number:</strong> {{ $preOrder->order_number }}</p>
        
        <p>You can download your accounts file attached to this email.</p>
        
        @if($preOrder->download_file)
            <p>Alternatively, you can download it from your dashboard.</p>
            <a href="{{ route('dashboard') }}" class="btn">Go to Dashboard</a>
        @endif
        
        <p>Thank you for your patience!</p>
        <p>Regards,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
