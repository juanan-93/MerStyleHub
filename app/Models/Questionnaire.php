<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Scope para cuestionarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope para cuestionarios inactivos
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Obtener las preguntas del cuestionario
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Usuarios asignados a este cuestionario
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'questionnaire_user')
            ->withPivot(['status', 'assigned_at', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * Obtener el conteo de preguntas
     */
    public function getQuestionsCountAttribute(): int
    {
        return $this->questions()->count();
    }

    /**
     * Verificar si el cuestionario está activo
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Obtener preguntas por tipo
     */
    public function getQuestionsByType(string $type)
    {
        return $this->questions()->where('type', $type)->get();
    }
}
