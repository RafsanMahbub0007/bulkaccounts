<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'status',
        'delivered_at',
        'accounts',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];


    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
