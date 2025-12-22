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
        Schema::create('orden_checklist_detalles', function (Blueprint $table) {
            $table->id();

            // Relación con la Orden de Servicio
            $table->foreignId('orden_servicio_id')->constrained('orden_servicios')->onDelete('cascade');
            
            // Contenido del Checklist (para la trazabilidad histórica del MP)
            $table->string('tarea');
            $table->boolean('completado')->default(false);
            $table->text('notas_resultado')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('orden_checklist_detalles', function (Blueprint $table) {
        // Eliminar claves foráneas
        // Asumiendo que tiene claves foráneas a orden_servicios y checklists
       // $table->dropForeign(['orden_servicio_id']); 
       // $table->dropForeign(['checklist_id']); // <-- ESTA ES CRÍTICA
    });
    Schema::dropIfExists('orden_checklist_detalles');
}
};
