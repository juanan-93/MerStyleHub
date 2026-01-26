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

    /**
     * Verifica si hay conflicto de horario con otras disponibilidades en la misma fecha
     * Excluyendo el batch_id actual (para ediciones)
     */
    public static function hasTimeConflict($date, $startTime, $endTime, $excludeBatchId = null)
    {
        $query = self::whereDate('date', $date);
        
        if ($excludeBatchId) {
            $query->where('batch_id', '!=', $excludeBatchId);
        }
        
        return $query->where(function($q) use ($startTime, $endTime) {
            $q->whereRaw("TIME(start_time) < TIME(?)", [$endTime])
              ->whereRaw("TIME(end_time) > TIME(?)", [$startTime]);
        })->exists();
    }

    /**
     * Obtiene todos los conflictos de horario para una fecha específica
     */
    public static function getConflicts($date, $startTime, $endTime, $excludeBatchId = null)
    {
        $query = self::whereDate('date', $date);
        
        if ($excludeBatchId) {
            $query->where('batch_id', '!=', $excludeBatchId);
        }
        
        return $query->where(function($q) use ($startTime, $endTime) {
            $q->whereRaw("TIME(start_time) < TIME(?)", [$endTime])
              ->whereRaw("TIME(end_time) > TIME(?)", [$startTime]);
        })->get();
    }
}
