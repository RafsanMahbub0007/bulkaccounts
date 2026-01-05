<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAccount extends Model
{
    protected $fillable = [
        'product_id', 'email','meta','status'
    ];

    protected $casts = [
        'meta' => 'array',
    ];
    public function product()
        {
            return $this->belongsTo(Product::class);
        }
}
