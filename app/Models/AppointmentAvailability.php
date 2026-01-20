<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentAvailability extends Model
{
    protected $fillable = [
        'batch_id',
        'title',
        'date',
        'start_time',
        'end_time',
        'duration',
        'category',
        'selection_type'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'availability_id');
    }
}
