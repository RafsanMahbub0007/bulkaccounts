<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
   protected $fillable = [
        'name',
        'category_id',
        'slug',
        'keywords',
        'description',
        'image',
        'order',
        'is_active'
    ];
    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class,'subcategory_id');
    }
}
