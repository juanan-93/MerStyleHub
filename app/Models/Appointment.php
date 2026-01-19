<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'availability_id',
        'date',
        'start_time',
        'end_time',
        'client_name',
        'client_email',
        'client_phone',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function availability()
    {
        return $this->belongsTo(AppointmentAvailability::class, 'availability_id');
    }
}
