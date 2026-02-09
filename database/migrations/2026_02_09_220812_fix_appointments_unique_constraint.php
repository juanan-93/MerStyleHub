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
            $table->dropUnique('appointments_slot_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->unique(['availability_id', 'date', 'start_time'], 'appointments_slot_unique');
        });
    }
};
