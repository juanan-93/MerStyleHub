<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * El constraint único anterior no consideraba el status, lo que impedía
     * reservar un slot que había sido cancelado. Este fix incluye el status
     * en el constraint, permitiendo múltiples registros para el mismo slot
     * siempre que no tengan el mismo status (ej: uno cancelled y uno confirmed).
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Primero eliminar la foreign key que usa el índice
            $table->dropForeign(['availability_id']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            // Ahora sí podemos eliminar el constraint único anterior
            $table->dropUnique('appointments_availability_id_date_start_time_unique');
        });

        Schema::table('appointments', function (Blueprint $table) {
            // Recrear la foreign key
            $table->foreign('availability_id')
                  ->references('id')
                  ->on('appointment_availabilities')
                  ->cascadeOnDelete();

            // Crear nuevo constraint único que incluye el status
            // Esto permite: un slot cancelled + un slot confirmed para el mismo horario
            $table->unique(['availability_id', 'date', 'start_time', 'status'], 'appointments_slot_status_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Eliminar la foreign key primero
            $table->dropForeign(['availability_id']);
            // Eliminar el nuevo constraint
            $table->dropUnique('appointments_slot_status_unique');
        });

        Schema::table('appointments', function (Blueprint $table) {
            // Restaurar el constraint original
            $table->unique(['availability_id', 'date', 'start_time'], 'appointments_availability_id_date_start_time_unique');
            // Recrear la foreign key
            $table->foreign('availability_id')
                  ->references('id')
                  ->on('appointment_availabilities')
                  ->cascadeOnDelete();
        });
    }
};
