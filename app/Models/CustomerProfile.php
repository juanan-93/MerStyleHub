<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'last_name',
        'phone',
        'age',
        'city',
        'profession',
        'phone_call_date',
        'product_id',
        'service_type',
        'service_completion_date',
        'percentage_paid',
        'payment_date',
        'percentage_pending',
        'style',
        'morphology',
        'colorimetry_id',
        'observations',
    ];

    protected $casts = [
        'phone_call_date' => 'date',
        'service_completion_date' => 'date',
        'payment_date' => 'date',
        'percentage_paid' => 'decimal:2',
        'percentage_pending' => 'decimal:2',
        'age' => 'integer',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con Product (servicio contratado)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relación con Colorimetry
    public function colorimetry()
    {
        return $this->belongsTo(Colorimetry::class);
    }
}
