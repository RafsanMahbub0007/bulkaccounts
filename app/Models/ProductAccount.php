<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAccount extends Model
{
    protected $fillable = [
        'product_id', 'email','meta','status','meta_headers','row_index'
    ];

    protected $casts = [
        'meta' => 'array',
        'meta_headers' => 'array',
    ];
    public function product()
        {
            return $this->belongsTo(Product::class);
        }
}
