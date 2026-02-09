<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Eliminar el unique constraint que no tiene en cuenta el status.
     * Esto causaba que al cancelar una cita y re-reservar el mismo slot,
     * la BD rechazaba la inserción por duplicado.
     * La unicidad se controla a nivel de código con lockForUpdate()
     * filtrando solo por status confirmed/pending.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // 1. Quitar la FK que depende del índice unique
            $table->dropForeign(['availability_id']);

            // 2. Ahora sí podemos eliminar el unique index
            $table->dropUnique('appointments_slot_unique');

            // 3. Crear un índice simple para que la FK pueda funcionar
            $table->index('availability_id', 'appointments_availability_id_index');

            // 4. Restaurar la FK sobre el nuevo índice
            $table->foreign('availability_id')
                  ->references('id')
                  ->on('appointment_availabilities')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Revertir: quitar FK, quitar índice simple, recrear unique, recrear FK
            $table->dropForeign(['availability_id']);
            $table->dropIndex('appointments_availability_id_index');
            $table->unique(['availability_id', 'date', 'start_time'], 'appointments_slot_unique');
            $table->foreign('availability_id')
                  ->references('id')
                  ->on('appointment_availabilities')
                  ->cascadeOnDelete();
        });
    }
};
