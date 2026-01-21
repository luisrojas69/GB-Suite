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
        Schema::create('labor_maquinaria_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registro_labor_id')->constrained('registro_labores')->onDelete('cascade');
            $table->foreignId('activo_id')->constrained('activos'); 
            $table->foreignId('operador_id')->nullable()->constrained('med_pacientes');
            $table->decimal('horometro_inicial', 12, 2);
            $table->decimal('horometro_final', 12, 2);
            $table->decimal('horas_desfase_uso', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('labor_maquinaria_detalle');
    }
};
