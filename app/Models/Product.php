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
        'display_order',
        'purchase_price',
        'selling_price',
        'stock',
        'min_order_qty',
        'product_icon',
        'product_image',
        'accounts_excel',
        'keywords',
        'description',
        'content',
        'google_sheet_url',
        'google_sheet_id',
        'sheet_meta',
        'is_active',
    ];

    protected $casts = [
        'feature_ids' => 'array',
        'sheet_meta' => 'array',
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
        return $this->belongsToMany(Offer::class, 'offer_product');
    }

    /* ACTIVE OFFER (only valid by date) */
    public function activeOffer()
    {
        return $this->offers()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
    }

    public function hasOffer()
    {
        return $this->activeOffer() !== null;
    }

    /* DISCOUNTED PRICE LOGIC */
    public function discountedPrice()
    {
        $offer = $this->activeOffer();

        if ($offer->discount_type === 'percentage') {
            return $this->selling_price - ($this->selling_price * ($offer->discount_value / 100));
        }

        if ($offer->discount_type === 'fixed') {
            return max(0, $this->selling_price - $offer->discount_value);
        }

        return $this->selling_price;
    }

    /* DISCOUNT PERCENT FOR BADGE */
    public function discountPercent()
    {
        $offer = $this->activeOffer();
        if (!$offer) return 0;

        return $offer->discount_type === 'percentage'
            ? round($offer->discount_value)
            : round(($offer->discount_value / $this->selling_price) * 100);
    }

    /* STOCK CHECK */
    public function outOfStock()
    {
        return $this->stock <= 0;
    }

    public function accounts()
    {
        return $this->hasMany(ProductAccount::class);
    }

    public function featureList()
    {
        if (!$this->feature_ids) {
            return [];
        }

        return ProductFeature::whereIn('id', $this->feature_ids)->pluck('name')->toArray();
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
