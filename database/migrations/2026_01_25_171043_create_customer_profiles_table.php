<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            
            // Relación con users
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            
            // Datos personales
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('age')->nullable();
            $table->string('city')->nullable();
            $table->string('profession')->nullable();
            
            // Gestión comercial
            $table->date('phone_call_date')->nullable(); // Día llamada telefónica
            $table->foreignId('product_id')->nullable() // Servicio contratado
                  ->constrained('products')
                  ->nullOnDelete();
            $table->date('service_completion_date')->nullable(); // Fecha finalización
            
            // Pagos
            $table->decimal('percentage_paid', 5, 2)->default(0.00); // % pagado (0-100)
            $table->date('payment_date')->nullable();
            $table->decimal('percentage_pending', 5, 2)->default(100.00); // % pendiente
            
            // Análisis de estilo
            $table->string('style')->nullable(); // Estilo
            $table->string('morphology')->nullable(); // Morfología
            $table->foreignId('colorimetry_id')->nullable() // Colorimetría
                  ->constrained('colorimetries')
                  ->nullOnDelete();
            
            // Notas
            $table->text('observations')->nullable();
            
            $table->timestamps();
            
            // Índice único para evitar duplicados
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_profiles');
    }
};
