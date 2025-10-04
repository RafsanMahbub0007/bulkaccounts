<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
       'name',
        'slug',
        'category_id',
        'subcategory_id',
        'features',
        'price',
        'stock',
        'min_order_qty',
        'product_icon',
        'product_image',
        'keywords',
        'description',
        'content',
    ];

    protected $casts = [
        'features' => 'array', 
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
