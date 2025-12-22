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
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            
            // 1. Relación con la Orden de Servicio
            // Esta es la columna que faltaba y causaba el error de SQL.
            // Es nullable si el registro de checklist también se usa como una "plantilla maestra" sin OS.
            $table->foreignId('orden_servicio_id')
                  ->nullable() 
                  ->constrained('orden_servicios') // Asume que tu tabla de órdenes se llama 'orden_servicios'
                  ->onDelete('cascade'); // Si se borra la OS, se borra su checklist de ejecución.
            
            // 2. Campos de Plantilla/Identificación
            $table->string('nombre')->comment('Ej: MP 250 Horas (Tractor John Deere)');
            $table->string('tipo_activo')->nullable()->comment('Para qué tipo de activo aplica: Tractor, Camioneta, etc.');
            $table->string('intervalo_referencia')->nullable()->comment('Ej: 250 HRS, 10000 KM, 6 Meses');
            $table->text('descripcion_tareas')->nullable()->comment('Copia del contenido del checklist maestro al momento de la creación de la OS.');

            // 3. Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};