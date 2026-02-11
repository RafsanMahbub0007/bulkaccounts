<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'meta_title', 'keywords', 'description', 'image', 'order', 'is_active'];

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function offers()
{
    return $this->belongsToMany(Offer::class, 'offer_category');
}

    public function subcategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }

}
