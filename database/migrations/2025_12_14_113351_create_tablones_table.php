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
        // NOTA: Si esta migración ya fue ejecutada, debe crear una nueva migración (add_columns_to_tablones_table)
        // en su lugar. Asumo que está lista para ser migrada por primera vez o revertida y migrada.
        Schema::create('tablones', function (Blueprint $table) {
            $table->id();

            // Claves Foráneas
            $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
            // La tabla 'variedades' se creará en el siguiente paso, pero la FK es necesaria aquí.
            $table->foreignId('variedad_id')->nullable()->constrained('variedades')->onDelete('set null')->comment('Última variedad de caña sembrada.');
            
            $table->string('codigo_tablon_interno', 5); // Ej: 01, AB
            $table->string('codigo_completo', 15)->unique(); // Ej: 010201, 0802AB
            $table->string('nombre', 100);
            
            // Campo de Área
            $table->decimal('area_ha', 8, 2)->comment('Área en Hectáreas del tablón.');
            
            // Campos de Control de Siembra y Metas
            $table->date('fecha_siembra')->nullable()->comment('Fecha de la última siembra o resoca (para control de edad).');
            $table->decimal('meta_ton_ha', 8, 2)->nullable()->comment('Meta de Toneladas por Hectárea esperada.');

            // Campos existentes
            $table->string('tipo_suelo', 50)->nullable();
            $table->enum('estado', ['Activo', 'Inactivo', 'Preparacion'])->default('Activo');
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            // Restricción de unicidad
            $table->unique(['lote_id', 'codigo_tablon_interno']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tablones');
    }
};