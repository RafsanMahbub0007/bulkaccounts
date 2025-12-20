@extends('layouts.app')

@section('slot')
<div class="bg-gray-900 text-white py-20 min-h-screen flex items-center justify-center">
    <div class="max-w-3xl w-full bg-gray-800 rounded-2xl shadow-xl p-8 text-center">

        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <i class="fa-solid fa-circle-check text-green-400 text-5xl"></i>
        </div>

        <h1 class="text-3xl font-bold text-green-400 mb-2">
            Payment Received
        </h1>

        <p class="text-gray-300 mb-6">
            Order Number: <span class="text-white font-semibold">{{ $order->order_number }}</span><br>
            Status: <span class="text-yellow-400 font-medium">{{ ucfirst($order->payment_status) }}</span>
        </p>

        <div class="flex gap-4 justify-center">
            <a href="{{ route('user.orders') }}" class="px-6 py-3 bg-green-500 rounded-xl hover:bg-green-600">
                View Orders
            </a>
            <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-700 rounded-xl hover:bg-gray-600">
                Go Home
            </a>
        </div>

        <livewire:payment-status :orderId="$order->id" />

    </div>
</div>
@endsection
