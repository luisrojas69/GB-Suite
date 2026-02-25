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
        Schema::create('molienda_ejecutada', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zafra_id')->constrained('zafras');
            $table->foreignId('tablon_id')->constrained('tablones');
            
            // Totales calculados de los boletos
            $table->decimal('toneladas_reales', 12, 3)->default(0)->comment('Suma de toneladas_netas de boletos_arrime');
            $table->decimal('rendimiento_real_avg', 5, 2)->default(0)->comment('Promedio de rendimiento de los boletos');
            $table->decimal('area_cosechada_real', 10, 2)->nullable()->comment('Hectáreas reales (pueden variar vs el plan)');
            
            // Fechas de operación real
            $table->date('fecha_inicio_cosecha')->nullable();
            $table->date('fecha_fin_cosecha')->nullable();
            
            // Estado del tablón en esta zafra
            $table->enum('estado_cosecha', ['En Proceso', 'Finalizado', 'Ajustado'])->default('En Proceso');
            
            $table->timestamps();

            // Un registro único por tablón en cada zafra para la comparación 1:1 con el Plan
            $table->unique(['zafra_id', 'tablon_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('molienda_ejecutada');
    }
};
