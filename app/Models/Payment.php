<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'paid_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected static function booted()
    {
        static::saved(function ($payment) {
            $payment->updateOrderPaymentStatus();
        });
    }

    public function updateOrderPaymentStatus()
    {
        $order = $this->order;

        if ($order) {
            $totalPaid = $order->payments->where('status', 'completed')->sum('amount');
            $totalPrice = $order->total_price;

            if ($totalPaid == 0) {
                $order->payment_status = 'unpaid';
            } elseif ($totalPaid < $totalPrice) {
                $order->payment_status = 'partially_paid';
            } elseif ($totalPaid >= $totalPrice) {
                if ($order->payments->every(fn($payment) => $payment->status === 'completed')) {
                    $order->payment_status = 'paid';
                }
            } else {
                $order->payment_status = 'unpaid';
            }

            $order->save();
        }
    }
}
