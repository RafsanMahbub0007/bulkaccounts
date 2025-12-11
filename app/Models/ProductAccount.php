<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAccount extends Model
{
    protected $fillable = [
        'product_id', 'email','meta','is_sold'
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
