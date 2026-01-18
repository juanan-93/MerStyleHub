<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentAvailability extends Model
{
    protected $fillable = ['batch_id', 'date', 'start_time', 'end_time', 'duration', 'category', 'selection_type'];
}
