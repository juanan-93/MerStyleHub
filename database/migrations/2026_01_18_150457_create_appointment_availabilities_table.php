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
        Schema::create('appointment_availabilities', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('batch_id')->nullable(); // after() solo funciona en alter, no en create
            $table->date('date'); // El día (ej: 2026-01-20)
            $table->time('start_time'); // Hora inicio (ej: 08:00)
            $table->time('end_time'); // Hora fin (ej: 18:00)
            $table->integer('duration'); // Duración en minutos (ej: 30)
            $table->string('category'); // Categoría (ej: standard)
            $table->string('selection_type')->default('range');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_availabilities');
        // Ya no necesitas dropColumn porque dropIfExists() borra toda la tabla
    }
};
