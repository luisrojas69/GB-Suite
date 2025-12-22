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
        Schema::create('med_consultas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('paciente_id')->constrained('med_pacientes');
        $table->string('motivo_consulta'); // Ej: Accidente, Control, Enfermedad Común
        
        // Signos Vitales
        $table->string('tension_arterial', 10)->nullable();
        $table->integer('frecuencia_cardiaca')->nullable(); // bpm
        $table->float('temperatura')->nullable(); // °C
        $table->integer('saturacion_oxigeno')->nullable(); // %
        
        // Diagnóstico y Tratamiento
        $table->text('anamnesis'); // Lo que el paciente cuenta
        $table->text('examen_fisico'); // Lo que el médico observa
        $table->string('diagnostico_cie10')->nullable(); // Código estándar médico
        $table->text('plan_tratamiento');
        
        // Reposo y Aptitud
        $table->boolean('genera_reposo')->default(false);
        $table->integer('dias_reposo')->default(0);
        $table->enum('aptitud', ['Apto', 'Apto con Restricción', 'No Apto'])->default('Apto');
        
        $table->foreignId('user_id')->constrained('users'); // Médico que atiende
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_consultas');
    }
};
