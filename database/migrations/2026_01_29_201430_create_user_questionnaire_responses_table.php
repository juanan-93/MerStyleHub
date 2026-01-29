<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Respuestas individuales a cada pregunta
        Schema::create('user_questionnaire_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_user_id')->constrained('questionnaire_user')->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_option_id')->nullable()->constrained()->onDelete('cascade'); // Para respuestas tipo test/select
            $table->text('text_response')->nullable(); // Para respuestas tipo texto
            $table->timestamps();
            
            // Un usuario solo puede responder una vez a cada pregunta de un cuestionario asignado
            $table->unique(['questionnaire_user_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_questionnaire_responses');
    }
};
