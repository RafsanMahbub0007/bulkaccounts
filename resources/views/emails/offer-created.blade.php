<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Offer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #007bff;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            text-align: center;
            color: #333;
        }
        .offer-title {
            font-size: 22px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .offer-description {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #555;
        }
        .discount-box {
            display: inline-block;
            background-color: #ffeb3b;
            color: #333;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #218838;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>New Special Offer!</h1>
    </div>

    <div class="content">
        <div class="offer-title">{{ $offer->title }}</div>

        @if($offer->discount_value)
            <div class="discount-box">
                Get {{ $offer->discount_value }}{{ $offer->discount_type == 'percentage' ? '%' : '$' }} OFF!
            </div>
        @endif

        <div class="offer-description">
            {!! nl2br(e($offer->description)) !!}
        </div>

        @if($offer->end_date)
            <p style="color: #d9534f; font-weight: bold;">Hurry! Offer ends on {{ \Carbon\Carbon::parse($offer->end_date)->format('F d, Y') }}</p>
        @endif

        <a href="{{ route('home') }}" class="btn">Shop Now</a>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>

</body>
</html>
