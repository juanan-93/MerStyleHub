<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price_presencial',
        'price_online',
        'is_active',
    ];

    protected $casts = [
        'price_presencial' => 'decimal:2',
        'price_online' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relación con CustomerProfile
    public function customerProfiles()
    {
        return $this->hasMany(CustomerProfile::class);
    }
}
