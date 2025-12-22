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
        // database/migrations/YYYY_MM_DD_create_categories_table.php
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('species_id')->constrained()->onDelete('cascade'); // Relación a Especie
            $table->string('name', 50); // Ej: Vaca, Novillo, Becerro, Potro, Cordero
            $table->string('cost_center_id', 4); // Ej: 5241, 5242, 5243
            $table->unique(['species_id', 'name']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
