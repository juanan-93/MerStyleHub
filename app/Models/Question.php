<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'text',
        'type',
        'order',
        'required',
    ];

    protected $casts = [
        'type' => 'string',
        'order' => 'integer',
        'required' => 'boolean',
    ];

    /**
     * Tipos de pregunta disponibles
     */
    const TYPE_TEST = 'test';
    const TYPE_TEXT = 'text';
    const TYPE_SELECT = 'select';

    /**
     * Obtener todos los tipos de pregunta
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_TEST => 'Test (opción múltiple)',
            self::TYPE_TEXT => 'Texto libre',
            self::TYPE_SELECT => 'Selector (dropdown)',
        ];
    }

    /**
     * Cuestionario al que pertenece la pregunta
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Opciones de la pregunta (para tipos test y select)
     */
    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }

    /**
     * Respuestas de usuarios a esta pregunta
     */
    public function responses(): HasMany
    {
        return $this->hasMany(UserQuestionnaireResponse::class);
    }

    /**
     * Verificar si la pregunta tiene opciones
     */
    public function hasOptions(): bool
    {
        return in_array($this->type, [self::TYPE_TEST, self::TYPE_SELECT]);
    }

    /**
     * Verificar si la pregunta es obligatoria
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Obtener el icono según el tipo de pregunta
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            self::TYPE_TEST => 'ti-list-check',
            self::TYPE_TEXT => 'ti-text-caption',
            self::TYPE_SELECT => 'ti-select',
            default => 'ti-question-mark',
        };
    }

    /**
     * Obtener el color según el tipo de pregunta
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_TEST => '#17a2b8',
            self::TYPE_TEXT => '#28a745',
            self::TYPE_SELECT => '#6f42c1',
            default => '#6c757d',
        };
    }
}
