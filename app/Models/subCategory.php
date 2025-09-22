<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subCategory extends Model
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
}
