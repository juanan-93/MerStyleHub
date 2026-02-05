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
        Schema::create('customer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')
                  ->constrained('customer_profiles')
                  ->cascadeOnDelete();
            
            $table->string('file_name'); // Nombre original del archivo
            $table->string('file_path'); // Ruta del archivo en storage
            $table->string('file_type')->nullable(); // Tipo MIME
            $table->unsignedBigInteger('file_size')->nullable(); // Tamaño en bytes
            $table->string('document_type')->nullable(); // Tipo de documento (contrato, identificación, etc.)
            $table->text('description')->nullable(); // Descripción opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_documents');
    }
};
