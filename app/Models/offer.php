<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class offer extends Model
{
     protected $fillable = [
        'title',
        'description',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'status',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'offer_category');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product');
    }
}
