<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class about extends Model
{
    protected $fillable = [
        'title',
        'about_image',
        'desctiption',
    ];
}
