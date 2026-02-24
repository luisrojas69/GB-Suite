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
            //$table->string('codigo_central', 5)->nullable(); // Por desarrollar - Esto es por si el central maneja un codigo diferente
            $table->string('codigo_completo', 15)->unique(); // Ej: 010201, 0802AB
            $table->string('nombre', 100);

            // Estado del Ciclo (La Inteligencia)
            $table->enum('tipo_ciclo', ['Plantilla', 'Soca'])->default('Plantilla');
            $table->integer('numero_soca')->default(0); 
            $table->date('fecha_inicio_ciclo')->nullable()->comment('Fecha de Siembra o Cosecha anterior');

            // El "Presupuesto" de Rendimiento (Central Pastora vs Granja)
            $table->decimal('meta_ton_ha', 8, 2)->nullable()->comment('Meta de Toneladas por Hectárea esperada.');

             // Datos Físicos y Operativos
            $table->decimal('hectareas_documento', 10, 2);
            $table->string('tipo_suelo', 50)->nullable();
            $table->enum('estado', ['Preparacion', 'Crecimiento', 'Maduro', 'Cosecha', 'Inactivo'])->default('Preparacion');

            // Campo espacial crítico
            $table->geometry('geometria')->nullable(); 

            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            // Restricción de unicidad.
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