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
        // database/migrations/YYYY_MM_DD_create_weighings_table.php
        Schema::create('weighings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->onDelete('cascade');
            $table->date('weighing_date')->index();
            $table->decimal('weight_kg', 8, 2)->comment('Peso registrado en kg. (Ej: 350.50)');
            $table->string('notes', 255)->nullable();
            
            // Para asegurar un único pesaje por animal por día (opcional)
            $table->unique(['animal_id', 'weighing_date']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weighings');
    }
};
