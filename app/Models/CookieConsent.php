<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'essential',
        'analytics',
        'marketing',
        'consented_at',
    ];

    protected $casts = [
        'essential' => 'boolean',
        'analytics' => 'boolean',
        'marketing' => 'boolean',
        'consented_at' => 'datetime',
    ];
}
