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
// database/migrations/YYYY_MM_DD_create_locations_table.php
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique(); // Ej: Caimana, La Uva, Haras
            $table->string('cost_center_id', 4); // Ej: 5241, 5242, 5243
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
