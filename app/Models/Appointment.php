<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'status',
        'notes',
        'cancellation_token'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Boot del modelo para generar token automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            $appointment->cancellation_token = Str::random(64);
        });
    }

    /**
     * Obtener la URL de cancelación
     */
    public function getCancellationUrlAttribute()
    {
        return url("/calendar/cancel/{$this->cancellation_token}");
    }

    public function availability()
    {
        return $this->belongsTo(AppointmentAvailability::class, 'availability_id');
    }

    /**
     * Scope para citas pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para citas confirmadas
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope para citas canceladas
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope para citas bloqueadas
     */
    public function scopeBlocked($query)
    {
        return $query->where('status', 'blocked');
    }

    /**
     * Scope para citas de hoy
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', now()->toDateString());
    }

    /**
     * Scope para citas futuras
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString())
                     ->orderBy('date')
                     ->orderBy('start_time');
    }

    /**
     * Obtener el color del estado
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'blocked' => 'dark',
            default => 'secondary'
        };
    }

    /**
     * Obtener la etiqueta del estado
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmada',
            'cancelled' => 'Cancelada',
            'blocked' => 'Bloqueada',
            default => 'Desconocido'
        };
    }

    /**
     * Verificar si es una cita bloqueada
     */
    public function isBlocked()
    {
        return $this->status === 'blocked';
    }

    /**
     * Verificar si la cita está en el pasado
     */
    public function isPast()
    {
        return $this->date->lt(now()->startOfDay());
    }

    /**
     * Verificar si la cita es hoy
     */
    public function isToday()
    {
        return $this->date->isToday();
    }
}
