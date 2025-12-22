<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Control de Campañas (Zafras).
     */
    public function up(): void
    {
        Schema::create('zafras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique(); // Ej: Zafra 2025/2026
            $table->integer('anio_inicio');
            $table->integer('anio_fin');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->enum('estado', ['Activa', 'Cerrada', 'Planeada'])->default('Planeada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zafras');
    }
};