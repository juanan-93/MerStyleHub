<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'text',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Pregunta a la que pertenece la opción
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
