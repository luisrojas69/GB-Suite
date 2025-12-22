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
        Schema::create('animal_costs', function (Blueprint $table) {
            $table->id();
            
            // Llave foránea al animal.
            $table->foreignId('animal_id')->constrained()->onDelete('cascade')->comment('Referencia al animal que posee este costo.');

            // Período y Centro de Costo
            // Nota: Se usará el primer día del mes como indicador del período.
            $table->date('period_date')->index()->comment('Fecha de inicio del periodo mensual de costo.');
            $table->string('cost_center_id', 4)->index()->comment('Centro de Costo de Profit Plus que generó el gasto (Ej: 5241).');

            // Valores de Costo
            $table->decimal('total_accumulated_expense', 18, 2)->comment('Gasto total acumulado del CeCo en Profit para el periodo (monto bruto).');
            $table->integer('active_animal_count')->comment('Número de animales activos que se usaron para el prorrateo.');
            $table->decimal('unit_cost', 18, 2)->comment('Costo unitario prorrateado (Gasto Total / Total Animales).');

            // Campo de validación para evitar duplicados
            $table->unique(['animal_id', 'period_date'], 'unique_animal_cost_per_month');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_costs');
    }
};