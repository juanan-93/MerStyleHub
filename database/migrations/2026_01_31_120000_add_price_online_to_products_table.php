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
        Schema::table('products', function (Blueprint $table) {
            // Renombrar el campo price existente a price_presencial
            $table->renameColumn('price', 'price_presencial');
        });

        Schema::table('products', function (Blueprint $table) {
            // Añadir el campo para precio online
            $table->decimal('price_online', 10, 2)->default(0.00)->after('price_presencial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price_online');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('price_presencial', 'price');
        });
    }
};
