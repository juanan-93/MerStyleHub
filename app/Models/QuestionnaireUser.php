<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionnaireUser extends Model
{
    use HasFactory;

    protected $table = 'questionnaire_user';

    protected $fillable = [
        'questionnaire_id',
        'user_id',
        'status',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Estados disponibles
     */
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';

    /**
     * Cuestionario asignado
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Usuario asignado
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Respuestas del usuario a las preguntas
     */
    public function responses(): HasMany
    {
        return $this->hasMany(UserQuestionnaireResponse::class);
    }

    /**
     * Verificar si el cuestionario está completado
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Verificar si el cuestionario está pendiente
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Marcar como completado
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
    }

    /**
     * Obtener el progreso (porcentaje de preguntas respondidas)
     */
    public function getProgressAttribute(): int
    {
        $totalQuestions = $this->questionnaire->questions()->count();
        
        if ($totalQuestions === 0) {
            return 100;
        }

        $answeredQuestions = $this->responses()->count();

        return (int) round(($answeredQuestions / $totalQuestions) * 100);
    }
}
