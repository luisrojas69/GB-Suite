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
        $table->date('fecha_consulta');
        $table->string('motivo_consulta'); // Ej: Accidente, Control, Enfermedad Común
        $table->boolean('requiere_examenes')->default(false);
        $table->enum('status_consulta', [
            'Pendiente por exámenes', 
            'Cerrada'
        ])->default('Cerrada');
    
        // Signos Vitales
        $table->string('tension_arterial', 10)->nullable();
        $table->integer('frecuencia_cardiaca')->nullable(); // bpm
        $table->float('temperatura')->nullable(); // °C
        $table->integer('saturacion_oxigeno')->nullable(); // %
        
        // Diagnóstico y Tratamiento
        $table->text('anamnesis'); // Lo que el paciente cuenta
        $table->text('examen_fisico'); // Lo que el médico observa
        $table->string('diagnostico_cie10')->nullable(); // Código estándar médico
        $table->string('diagnostico_descripcion')->nullable();
        $table->text('plan_tratamiento');

        // Reposo y Aptitud
        $table->boolean('genera_reposo')->default(false);
        $table->integer('dias_reposo')->default(0);
        $table->boolean('reincorporado')->default(false)->comment('Indica si el reposo ya tuvo su chequeo de retorno');
        $table->boolean('tiene_accidente_vinculado')->default(false)->comment('Indica si la consulta tiene un acidnte vinculado');
        $table->unsignedBigInteger('accidente_id')->nullable();
        // $table->foreign('accidente_id')->references('id')->on('med_accidentes');
        // $table->unsignedBigInteger('orden_id')->nullable();
        // $table->foreign('orden_id')->references('id')->on('med_ordenes_examenes');
        // $table->boolean('consulta_rapida')->default(false);
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
