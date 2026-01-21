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
        Schema::create('labor_tablon_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_labor_id')->constrained('registro_labores')->onDelete('cascade');
            $table->foreignId('tablon_id')->constrained('tablones');
            $table->decimal('hectareas_logradas', 10, 2);
            // Si la labor fue siembra, guardamos la variedad aquí
            $table->foreignId('variedad_id')->nullable()->constrained('variedades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labor_tablon_detalle');
    }
};
