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
     * El problema: la restricción única actual incluye el status, lo que impide
     * tener múltiples citas canceladas en el mismo slot. Esto causa error 500
     * cuando el admin intenta cancelar una cita si ya existe otra cancelada.
     * 
     * La solución: Remover el status del constraint único y dejar solo
     * availability_id + date + start_time. Pero antes de aplicarlo,
     * limpiamos citas duplicadas canceladas para evitar conflictos.
     */
    public function up(): void
    {
        // Primero, eliminar TODAS las citas duplicadas (manteniendo solo la más reciente)
        // Esto incluye citas canceladas, confirmadas, pending, etc.
        DB::statement("
            DELETE t1 FROM appointments t1
            INNER JOIN appointments t2 
            WHERE t1.id < t2.id 
            AND t1.availability_id = t2.availability_id 
            AND t1.date = t2.date 
            AND t1.start_time = t2.start_time
        ");
        
        // Verificar qué constraint existe actualmente
        $indexExists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
            AND table_name = 'appointments' 
            AND index_name = 'appointments_slot_status_unique'
        ");
        
        if ($indexExists[0]->count > 0) {
            // Eliminar el constraint único actual que incluye status
            DB::statement("ALTER TABLE appointments DROP INDEX appointments_slot_status_unique");
        }

        // Crear nuevo constraint único SIN status
        // Esto permite solo UNA cita por slot, sin importar el status
        DB::statement("
            ALTER TABLE appointments 
            ADD UNIQUE KEY appointments_slot_unique (availability_id, date, start_time)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar el constraint nuevo
        DB::statement("ALTER TABLE appointments DROP INDEX appointments_slot_unique");
        
        // Restaurar el constraint anterior con status
        DB::statement("
            ALTER TABLE appointments 
            ADD UNIQUE KEY appointments_slot_status_unique (availability_id, date, start_time, status)
        ");
    }
};
