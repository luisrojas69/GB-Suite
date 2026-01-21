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
        Schema::create('activos', function (Blueprint $table) {
            $table->id();
            // Identificación
            $table->string('codigo')->unique()->comment('GBT01, GBC03, etc.');
            $table->string('nombre');
            $table->string('placa')->nullable();
            $table->enum('tipo', ['Tractor', 'Camión', 'Camioneta', 'Moto', 'Cosechadora', 'Zorra', 'Otro']);
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();

            
            // Uso y Status
            $table->string('departamento_asignado')->comment('Siembra, Cosecha, Administración, etc.');
            $table->enum('estado_operativo', ['Operativo', 'En Mantenimiento', 'Fuera de Servicio', 'Desincorporado'])->default('Operativo');
            $table->integer('lectura_actual')->default(0)->comment('Kilometraje u Horas de Uso');
            $table->enum('unidad_medida', ['KM', 'HRS'])->comment('Determina si el valor es KM o Horas');
            $table->string('imagen')->nullable();
            // Fechas y metadata
            $table->date('fecha_adquisicion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activos');
    }
};
