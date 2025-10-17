<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAccount extends Model
{
    protected $fillable = [
        'product_id', 'email', 'password_encrypted', 'two_fa_secret_encrypted', 'meta'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    // Encrypt/decrypt automatically
    public function setPasswordEncryptedAttribute($value)
    {
        $this->attributes['password_encrypted'] = $value ? encrypt($value) : null;
    }

    public function getPasswordEncryptedAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    public function setTwoFaSecretEncryptedAttribute($value)
    {
        $this->attributes['two_fa_secret_encrypted'] = $value ? encrypt($value) : null;
    }

    public function getTwoFaSecretEncryptedAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }
}
