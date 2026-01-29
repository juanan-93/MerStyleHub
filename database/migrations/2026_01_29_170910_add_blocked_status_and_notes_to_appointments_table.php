<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el ENUM de status para incluir 'blocked'
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'blocked') DEFAULT 'pending'");
        
        // Hacer client_phone nullable
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('client_phone')->nullable()->change();
        });
        
        // Agregar columna notes si no existe
        if (!Schema::hasColumn('appointments', 'notes')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->text('notes')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir el ENUM de status
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending'");
        
        // Revertir client_phone a no nullable
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('client_phone')->nullable(false)->change();
        });
        
        // Eliminar columna notes si existe
        if (Schema::hasColumn('appointments', 'notes')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('notes');
            });
        }
    }
};
