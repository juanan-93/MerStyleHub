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
        Schema::create('appointment_availability_user', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Indexes
            $table->index('batch_id');
            $table->index('user_id');
            
            // Evitar duplicados
            $table->unique(['batch_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_availability_user');
    }
};
