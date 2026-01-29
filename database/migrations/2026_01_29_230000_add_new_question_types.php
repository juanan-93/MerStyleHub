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
        // Modificar el ENUM para añadir los nuevos tipos: file e info
        DB::statement("ALTER TABLE questions MODIFY COLUMN type ENUM('test', 'text', 'select', 'file', 'info') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al ENUM original
        DB::statement("ALTER TABLE questions MODIFY COLUMN type ENUM('test', 'text', 'select') NOT NULL");
    }
};
