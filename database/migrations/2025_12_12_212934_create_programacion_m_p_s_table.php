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
        Schema::create('programacion_mps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activo_id')->constrained('activos')->onDelete('cascade');
            $table->foreignId('checklist_id')->constrained('checklists')->onDelete('cascade');
            
            // Valor meta para el próximo mantenimiento
            $table->integer('proximo_valor_lectura'); // Ej: 500 (la meta de horas/km)
            $table->date('proxima_fecha_mantenimiento')->nullable(); // Para los MPs basados en tiempo (ej. Anual)

            $table->integer('ultimo_valor_ejecutado');
            $table->date('ultima_ejecucion_fecha');
            
            $table->enum('status', ['Vigente', 'Proximo a Vencer', 'Vencido', 'Completado'])->default('Vigente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programacion_mps', function (Blueprint $table) {
            // Eliminar claves foráneas antes de eliminar la tabla
            $table->dropForeign(['activo_id']); 
            $table->dropForeign(['checklist_id']); // <-- ESTA ES CRÍTICA
        });
        Schema::dropIfExists('programacion_mps');
    }
};
