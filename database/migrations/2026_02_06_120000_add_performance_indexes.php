<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añadir índices para optimizar las consultas del dashboard de usuario.
     */
    public function up(): void
    {
        // Índices en appointments para búsquedas por email + fecha + status
        Schema::table('appointments', function (Blueprint $table) {
            $table->index(['client_email', 'date', 'status'], 'idx_appointments_email_date_status');
            $table->index(['date', 'status'], 'idx_appointments_date_status');
        });

        // Índices en appointment_availabilities para búsquedas por fecha + batch_id
        Schema::table('appointment_availabilities', function (Blueprint $table) {
            $table->index(['date', 'batch_id'], 'idx_availabilities_date_batch');
            $table->index('batch_id', 'idx_availabilities_batch');
        });

        // Índice en questionnaire_user para conteo por user_id + status
        Schema::table('questionnaire_user', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'idx_questionnaire_user_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('idx_appointments_email_date_status');
            $table->dropIndex('idx_appointments_date_status');
        });

        Schema::table('appointment_availabilities', function (Blueprint $table) {
            $table->dropIndex('idx_availabilities_date_batch');
            $table->dropIndex('idx_availabilities_batch');
        });

        Schema::table('questionnaire_user', function (Blueprint $table) {
            $table->dropIndex('idx_questionnaire_user_status');
        });
    }
};
