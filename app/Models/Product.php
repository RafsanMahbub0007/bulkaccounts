<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    private bool $activeOfferResolved = false;
    private ?Offer $activeOfferCache = null;
    private static array $featureNamesById = [];

    protected $fillable = [
        'name',
        'slug',
        'meta_title',
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
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_product');
    }

    /* ACTIVE OFFER (only valid by date) */
    public function activeOffer()
    {
        if ($this->activeOfferResolved) {
            return $this->activeOfferCache;
        }

        $now = now();
        $offer = null;

        if ($this->relationLoaded('offers')) {
            $offer = $this->offers
                ->first(fn ($offer) => $offer->start_date <= $now && $offer->end_date >= $now);
        } else {
            $offer = $this->offers()
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->first();
        }

        $this->activeOfferCache = $offer;
        $this->activeOfferResolved = true;

        return $offer;
    }

    public function hasOffer()
    {
        return $this->activeOffer() !== null;
    }

    /* DISCOUNTED PRICE LOGIC */
    public function discountedPrice()
    {
        $offer = $this->activeOffer();

        if (!$offer) {
            return $this->selling_price;
        }

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
        if (!$offer) {
            return 0;
        }

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

        $ids = collect($this->feature_ids)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (!$ids) {
            return [];
        }

        $missing = array_values(array_diff($ids, array_keys(self::$featureNamesById)));
        if ($missing) {
            ProductFeature::query()
                ->whereIn('id', $missing)
                ->pluck('name', 'id')
                ->each(function ($name, $id) {
                    self::$featureNamesById[(int) $id] = $name;
                });
        }

        return collect($ids)
            ->map(fn ($id) => self::$featureNamesById[$id] ?? null)
            ->filter()
            ->values()
            ->all();
    }

    public static function warmFeatureCache(array $ids): void
    {
        $ids = collect($ids)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (!$ids) {
            return;
        }

        $missing = array_values(array_diff($ids, array_keys(self::$featureNamesById)));

        if (!$missing) {
            return;
        }

        ProductFeature::query()
            ->whereIn('id', $missing)
            ->pluck('name', 'id')
            ->each(function ($name, $id) {
                self::$featureNamesById[(int) $id] = $name;
            });
    }

    public function primaryImageUrl(): string
    {
        return image_path($this->product_image ?: $this->subCategory?->image);
    }

    public function seoTitle(?Setting $setting = null): string
    {
        $setting ??= Setting::query()->find(1);

        return $this->meta_title ?: ($this->name . ' - ' . ($this->category?->name ?? 'Product') . ' - ' . ($setting->website_name ?? config('app.name')));
    }

    public function seoDescription(): string
    {
        return $this->description ?: Str::limit(strip_tags($this->content ?: ('Buy ' . $this->name . ' at best prices.')), 160);
    }

    public function currentSeoPrice(): float
    {
        return (float) ($this->hasOffer() ? $this->discountedPrice() : $this->selling_price);
    }

    public function soldCount(): int
    {
        return (int) $this->orderItems()
            ->whereHas('order', function ($query) {
                $query->where('order_status', 'completed')
                    ->where('payment_status', 'paid');
            })
            ->sum('quantity');
    }

    public function schemaMarkup(?Setting $setting = null): array
    {
        $setting ??= Setting::query()->find(1);

        $offer = $this->activeOffer();
        $productUrl = route('product.details', $this->slug);
        $categoryName = collect([$this->category?->name, $this->subCategory?->name])
            ->filter()
            ->implode(' > ');

        $additionalProperties = collect([
            [
                '@type' => 'PropertyValue',
                'name' => 'Minimum Order Quantity',
                'value' => (string) max(1, (int) $this->min_order_qty),
            ],
            [
                '@type' => 'PropertyValue',
                'name' => 'Stock',
                'value' => (string) max(0, (int) $this->stock),
            ],
        ])
            ->merge(
                collect($this->featureList())->map(fn ($feature) => [
                    '@type' => 'PropertyValue',
                    'name' => 'Feature',
                    'value' => $feature,
                ])
            )
            ->values()
            ->all();

        $productSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $this->name,
            'url' => $productUrl,
            'image' => [$this->primaryImageUrl()],
            'description' => $this->seoDescription(),
            'sku' => $this->slug,
            'mpn' => $this->slug,
            'brand' => [
                '@type' => 'Brand',
                'name' => $setting->website_name ?? config('app.name'),
            ],
            'category' => $categoryName ?: 'Digital Product',
            'itemCondition' => 'https://schema.org/NewCondition',
            'additionalProperty' => $additionalProperties,
            'offers' => [
                '@type' => 'Offer',
                'url' => $productUrl,
                'priceCurrency' => 'USD',
                'price' => number_format($this->currentSeoPrice(), 2, '.', ''),
                'priceValidUntil' => $offer?->end_date ? date('Y-m-d', strtotime((string) $offer->end_date)) : now()->addMonth()->toDateString(),
                'availability' => 'https://schema.org/' . ($this->stock > 0 ? 'InStock' : 'OutOfStock'),
                'inventoryLevel' => [
                    '@type' => 'QuantitativeValue',
                    'value' => max(0, (int) $this->stock),
                ],
                'seller' => [
                    '@type' => 'Organization',
                    'name' => $setting->website_name ?? config('app.name'),
                    'url' => url('/'),
                ],
            ],
        ];

        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_values(array_filter([
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => url('/'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Pricing',
                    'item' => route('pricing'),
                ],
                $this->category?->slug ? [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $this->category->name,
                    'item' => route('category.details', $this->category->slug),
                ] : null,
                ($this->category?->slug && $this->subCategory?->slug) ? [
                    '@type' => 'ListItem',
                    'position' => 4,
                    'name' => $this->subCategory->name,
                    'item' => route('subcategory.details', [$this->category->slug, $this->subCategory->slug]),
                ] : null,
                [
                    '@type' => 'ListItem',
                    'position' => $this->subCategory?->slug ? 5 : ($this->category?->slug ? 4 : 3),
                    'name' => $this->name,
                    'item' => $productUrl,
                ],
            ])),
        ];

        return [$productSchema, $breadcrumbSchema];
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
