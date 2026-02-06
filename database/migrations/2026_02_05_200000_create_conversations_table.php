<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            // Un admin y un customer solo pueden tener una conversación entre ellos
            $table->unique(['admin_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
