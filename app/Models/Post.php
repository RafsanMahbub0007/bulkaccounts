<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'image',
        'description',
        'content',
        'keywords',
        'author_id',
        'published'
    ];

    // Define the relationship to the User (Author)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
