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
        Schema::create('mantenimientos_correctivos', function (Blueprint $table) {
            $table->id();
            
            // Clave Foránea a la nueva tabla 'pozos_y_estaciones'
            $table->foreignId('id_activo')
                  ->constrained('pozos_y_estaciones') 
                  ->onDelete('cascade');
            
            // Registro de Falla
            $table->timestamp('fecha_falla_reportada');
            $table->string('sintoma_falla', 500);
            $table->string('responsable')->nullable();
            
            // Registro de Cierre
            $table->text('trabajo_realizado')->nullable();
            $table->timestamp('fecha_reinicio_operacion')->nullable();
            $table->float('tiempo_parada_horas')->nullable(); 
            $table->decimal('costo_asociado', 10, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimientos_correctivos');
    }
};