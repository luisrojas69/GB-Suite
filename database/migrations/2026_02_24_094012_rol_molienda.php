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
        Schema::create('rol_molienda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zafra_id')->constrained('zafras')->onDelete('cascade');
            $table->foreignId('tablon_id')->constrained('tablones')->onDelete('cascade');
            $table->foreignId('variedad_id')->constrained('variedades')->onDelete('no action');
            
            // El Ciclo
            $table->string('clase_ciclo', 20)->comment('Plantilla, Soca 1, Soca 2, etc.');
            
            // Las Metas (Presupuesto)
            $table->decimal('area_estimada_has', 10, 2)->comment('Hectáreas a cosechar en esta zafra');
            $table->decimal('ton_ha_estimadas', 8, 2)->comment('Toneladas por Hectárea esperadas');
            $table->decimal('toneladas_estimadas', 10, 2)->nullable();
            $table->decimal('rendimiento_esperado', 5, 2)->default(7.00)->comment('Rendimiento base esperado');
            $table->date('fecha_corte_proyectada')->nullable()->comment('Mes/Fecha tentativa de cosecha');
            
            $table->timestamps();

            // Restricción: Un tablón solo puede tener UN plan por zafra
            $table->unique(['zafra_id', 'tablon_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_molienda');
    }
};
