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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // appointment_cancelled, questionnaire_assigned, appointment_reminder, system, etc.
            $table->string('title');
            $table->text('message');
            $table->string('icon')->default('ti-bell'); // Icono de Tabler Icons
            $table->string('icon_color')->default('primary'); // primary, success, warning, danger, info
            $table->string('action_url')->nullable(); // URL para redirigir al hacer click
            $table->json('data')->nullable(); // Datos adicionales en JSON
            $table->timestamp('read_at')->nullable(); // Null = no leída
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
