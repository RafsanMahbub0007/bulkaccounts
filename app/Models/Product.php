<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Runtime caches
    |--------------------------------------------------------------------------
    */

    private bool $activeOfferResolved = false;

    private ?Offer $activeOfferCache = null;

    private static array $featureNamesById = [];

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'feature_ids' => 'array',
        'sheet_meta' => 'array',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'min_order_qty' => 'integer',
        'display_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(
            SubCategory::class,
            'subcategory_id'
        );
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function offers()
    {
        return $this->belongsToMany(
            Offer::class,
            'offer_product'
        );
    }

    public function accounts()
    {
        return $this->hasMany(ProductAccount::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Active Offer
    |--------------------------------------------------------------------------
    */

    public function activeOffer(): ?Offer
    {
        if ($this->activeOfferResolved) {
            return $this->activeOfferCache;
        }

        $this->activeOfferCache = null;

        /*
         * If offers were eager loaded, NEVER query the database again.
         */
        if ($this->relationLoaded('offers')) {
            $now = now();

            $this->activeOfferCache = $this->offers
                ->first(
                    fn (Offer $offer) =>
                        $offer->start_date <= $now &&
                        $offer->end_date >= $now
                );
        } else {
            /*
             * Fallback for places where Product is used individually.
             */
            $this->activeOfferCache = $this->offers()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->orderByDesc('end_date')
                ->first();
        }

        $this->activeOfferResolved = true;

        return $this->activeOfferCache;
    }

    public function hasOffer(): bool
    {
        return $this->activeOffer() !== null;
    }

    /*
    |--------------------------------------------------------------------------
    | Discounted Price
    |--------------------------------------------------------------------------
    */

    public function discountedPrice(): float
    {
        $offer = $this->activeOffer();

        if (!$offer) {
            return (float) $this->selling_price;
        }

        $price = (float) $this->selling_price;
        $discount = (float) $offer->discount_value;

        return match ($offer->discount_type) {
            'percentage' => max(
                0,
                $price - ($price * ($discount / 100))
            ),

            'fixed' => max(
                0,
                $price - $discount
            ),

            default => $price,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Discount Percentage
    |--------------------------------------------------------------------------
    */

    public function discountPercent(): int
    {
        $offer = $this->activeOffer();

        if (!$offer) {
            return 0;
        }

        if ($offer->discount_type === 'percentage') {
            return (int) round(
                (float) $offer->discount_value
            );
        }

        $price = (float) $this->selling_price;

        if ($price <= 0) {
            return 0;
        }

        return (int) round(
            ((float) $offer->discount_value / $price) * 100
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Stock
    |--------------------------------------------------------------------------
    */

    public function outOfStock(): bool
    {
        return (int) $this->stock <= 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Feature List
    |--------------------------------------------------------------------------
    */

    public function featureList(): array
    {
        if (empty($this->feature_ids)) {
            return [];
        }

        $ids = collect($this->feature_ids)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (empty($ids)) {
            return [];
        }

        $missing = array_values(
            array_diff(
                $ids,
                array_keys(self::$featureNamesById)
            )
        );

        if (!empty($missing)) {
            self::warmFeatureCache($missing);
        }

        return collect($ids)
            ->map(
                fn (int $id) =>
                    self::$featureNamesById[$id] ?? null
            )
            ->filter()
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Feature Cache
    |--------------------------------------------------------------------------
    */

    public static function warmFeatureCache(array $ids): void
    {
        $ids = collect($ids)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (empty($ids)) {
            return;
        }

        $missing = array_values(
            array_diff(
                $ids,
                array_keys(self::$featureNamesById)
            )
        );

        if (empty($missing)) {
            return;
        }

        /*
         * One query for all missing feature IDs.
         */
        ProductFeature::query()
            ->whereIn('id', $missing)
            ->pluck('name', 'id')
            ->each(function ($name, $id) {
                self::$featureNamesById[(int) $id] = $name;
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Image
    |--------------------------------------------------------------------------
    */

    public function primaryImageUrl(): string
    {
        return image_path(
            $this->product_image
                ?: $this->subCategory?->image
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SEO Title
    |--------------------------------------------------------------------------
    */

    public function seoTitle(?Setting $setting = null): string
    {
        /*
         * Prefer passing the already loaded Setting model.
         *
         * This prevents:
         *
         * Product 1 -> query settings
         * Product 2 -> query settings
         * Product 3 -> query settings
         *
         * etc.
         */
        $setting ??= Cache::remember(
            'system:settings',
            now()->addMinutes(30),
            fn () => Setting::query()->first()
        );

        return $this->meta_title
            ?: (
                $this->name
                . ' - '
                . ($this->category?->name ?? 'Product')
                . ' - '
                . ($setting?->website_name ?? config('app.name'))
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SEO Description
    |--------------------------------------------------------------------------
    */

    public function seoDescription(): string
    {
        return $this->description
            ?: Str::limit(
                strip_tags(
                    $this->content
                        ?: 'Buy ' . $this->name . ' at best prices.'
                ),
                160
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Current SEO Price
    |--------------------------------------------------------------------------
    */

    public function currentSeoPrice(): float
    {
        return $this->hasOffer()
            ? $this->discountedPrice()
            : (float) $this->selling_price;
    }

    /*
    |--------------------------------------------------------------------------
    | Sold Count
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | The preferred way is to preload this value using withSum()
    | from the Products query.
    |
    */

    public function soldCount(): int
    {
        /*
         * If withSum() was used, return the already-loaded value.
         *
         * Attribute name:
         * completed_order_items_sum_quantity
         */
        if (array_key_exists(
            'completed_order_items_sum_quantity',
            $this->attributes
        )) {
            return (int) (
                $this->attributes[
                    'completed_order_items_sum_quantity'
                ] ?? 0
            );
        }

        /*
         * Fallback for individual product pages.
         */
        return (int) $this->orderItems()
            ->whereHas('order', function ($query) {
                $query
                    ->where('order_status', 'completed')
                    ->where('payment_status', 'paid');
            })
            ->sum('quantity');
    }

    /*
    |--------------------------------------------------------------------------
    | Schema Markup
    |--------------------------------------------------------------------------
    */

    public function schemaMarkup(?Setting $setting = null): array
    {
        $setting ??= Cache::remember(
            'system:settings',
            now()->addMinutes(30),
            fn () => Setting::query()->first()
        );

        $offer = $this->activeOffer();

        $productUrl = route(
            'product.details',
            $this->slug
        );

        /*
         * These relationships should already be eager loaded
         * by the caller.
         */
        $categoryName = collect([
            $this->category?->name,
            $this->subCategory?->name,
        ])
            ->filter()
            ->implode(' > ');

        $additionalProperties = collect([
            [
                '@type' => 'PropertyValue',
                'name' => 'Minimum Order Quantity',
                'value' => (string) max(
                    1,
                    (int) $this->min_order_qty
                ),
            ],
            [
                '@type' => 'PropertyValue',
                'name' => 'Stock',
                'value' => (string) max(
                    0,
                    (int) $this->stock
                ),
            ],
        ])
            ->merge(
                collect($this->featureList())
                    ->map(fn ($feature) => [
                        '@type' => 'PropertyValue',
                        'name' => 'Feature',
                        'value' => $feature,
                    ])
            )
            ->values()
            ->all();

        $websiteName =
            $setting?->website_name
            ?? config('app.name');

        $productSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',

            'name' => $this->name,

            'url' => $productUrl,

            'image' => [
                $this->primaryImageUrl(),
            ],

            'description' => $this->seoDescription(),

            'sku' => $this->slug,

            'mpn' => $this->slug,

            'brand' => [
                '@type' => 'Brand',
                'name' => $websiteName,
            ],

            'category' =>
                $categoryName ?: 'Digital Product',

            'itemCondition' =>
                'https://schema.org/NewCondition',

            'additionalProperty' =>
                $additionalProperties,

            'offers' => [
                '@type' => 'Offer',

                'url' => $productUrl,

                'priceCurrency' => 'USD',

                'price' => number_format(
                    $this->currentSeoPrice(),
                    2,
                    '.',
                    ''
                ),

                'priceValidUntil' =>
                    $offer?->end_date
                        ? date(
                            'Y-m-d',
                            strtotime(
                                (string) $offer->end_date
                            )
                        )
                        : now()
                            ->addMonth()
                            ->toDateString(),

                'availability' =>
                    'https://schema.org/'
                    . (
                        $this->stock > 0
                            ? 'InStock'
                            : 'OutOfStock'
                    ),

                'inventoryLevel' => [
                    '@type' => 'QuantitativeValue',
                    'value' => max(
                        0,
                        (int) $this->stock
                    ),
                ],

                'seller' => [
                    '@type' => 'Organization',
                    'name' => $websiteName,
                    'url' => url('/'),
                ],
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Breadcrumb
        |--------------------------------------------------------------------------
        */

        $breadcrumbItems = [
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
        ];

        if ($this->category?->slug) {
            $breadcrumbItems[] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $this->category->name,
                'item' => route(
                    'category.details',
                    $this->category->slug
                ),
            ];
        }

        if (
            $this->category?->slug &&
            $this->subCategory?->slug
        ) {
            $breadcrumbItems[] = [
                '@type' => 'ListItem',
                'position' => 4,
                'name' => $this->subCategory->name,
                'item' => route(
                    'subcategory.details',
                    [
                        $this->category->slug,
                        $this->subCategory->slug,
                    ]
                ),
            ];
        }

        $breadcrumbItems[] = [
            '@type' => 'ListItem',
            'position' => count($breadcrumbItems) + 1,
            'name' => $this->name,
            'item' => $productUrl,
        ];

        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbItems,
        ];

        return [
            $productSchema,
            $breadcrumbSchema,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::deleting(function ($product) {

            $product->accounts()->delete();

            if ($product->accounts_excel) {
                Storage::disk('public')->delete(
                    $product->accounts_excel
                );
            }

            if ($product->product_image) {
                Storage::disk('public')->delete(
                    $product->product_image
                );
            }
        });
    }
}
