<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model
    // protected $table = 'your_table_name';

    // Specify the columns that can be mass-assigned
    protected $fillable = [
        'key', // Unique key
        'type', // Type
        'group', // Group
        'value', // Value
    ];

    // No need for a cast since there is no JSON field anymore
}
