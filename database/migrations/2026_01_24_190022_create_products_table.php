<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título del producto/servicio
            $table->decimal('price', 10, 2)->default(0.00); // Precio (€)
            $table->text('description')->nullable(); // Descripción detallada (HTML del editor)
            $table->boolean('is_active')->default(true); // Activo/Inactivo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
