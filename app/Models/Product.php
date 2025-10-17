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
        'feature_ids',
        'purchase_price',
        'selling_price',
        'stock',
        'min_order_qty',
        'product_icon',
        'product_image',
        'keywords',
        'description',
        'content',
    ];

    protected $casts = [
        'feature_ids' => 'array',
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

    public function features()
    {
        return $this->belongsToMany(ProductFeature::class, 'product_feature');
    }
    public function offers()
    {
        return $this->belongsToMany(offer::class, 'offer_product');
    }
    public function accounts()
    {
        return $this->hasMany(ProductAccount::class);
    }
    protected static function booted()
    {
        static::deleting(function ($product) {
            $product->accounts()->delete();

            if ($product->accounts_excel) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->accounts_excel);
            }
            if ($product->product_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->product_image);
            }
        });
    }
}
