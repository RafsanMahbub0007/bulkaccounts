<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guideline extends Model
{
    protected $fillable =[
        'title',
        'youtube_link',
        'details',
    ];
}
