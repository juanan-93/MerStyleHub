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
        Schema::create('questionnaire_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'completed'])->default('pending'); // Estado de la asignación
            $table->timestamp('assigned_at')->useCurrent(); // Fecha de asignación
            $table->timestamp('completed_at')->nullable(); // Fecha de completado
            $table->timestamps();
            
            // Un usuario solo puede tener asignado un cuestionario una vez
            $table->unique(['questionnaire_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_user');
    }
};
