<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('body')->nullable(); // Texto del mensaje
            $table->string('attachment_path')->nullable(); // Ruta del archivo adjunto
            $table->string('attachment_name')->nullable(); // Nombre original del archivo
            $table->string('attachment_type')->nullable(); // MIME type del archivo
            $table->unsignedBigInteger('attachment_size')->nullable(); // Tamaño en bytes
            $table->timestamp('read_at')->nullable(); // Cuándo fue leído por el receptor
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
            $table->index(['sender_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
