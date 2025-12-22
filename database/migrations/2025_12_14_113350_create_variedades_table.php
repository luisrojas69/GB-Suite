<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catálogo de Tipos de Caña.
     * De aquí se toma la FK para la tabla 'tablones'.
     */
    public function up(): void
    {
        Schema::create('variedades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('codigo', 10)->nullable()->unique();
            $table->decimal('meta_pol_cana', 8, 2)->nullable()->comment('Meta de Polarización (dulzor) esperada para la variedad.');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variedades');
    }
};