<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuestionnaireResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_user_id',
        'question_id',
        'question_option_id',
        'text_response',
    ];

    /**
     * Asignación de cuestionario al usuario
     */
    public function questionnaireUser(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireUser::class);
    }

    /**
     * Pregunta a la que corresponde la respuesta
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Opción seleccionada (para preguntas tipo test/select)
     */
    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'question_option_id');
    }

    /**
     * Obtener la respuesta formateada
     */
    public function getFormattedResponseAttribute(): string
    {
        if ($this->question_option_id) {
            return $this->selectedOption?->text ?? 'Opción no encontrada';
        }

        return $this->text_response ?? '';
    }
}
