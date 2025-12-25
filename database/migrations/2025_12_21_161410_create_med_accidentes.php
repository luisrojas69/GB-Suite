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
        Schema::create('med_accidentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('med_pacientes');
           
            
            // Datos del Evento
            $table->dateTime('fecha_hora_accidente');
            $table->string('lugar_exacto'); // Ej: Galpón 4, Taller Mecánico, Lote de Caña X
            $table->string('tipo_evento'); // Accidente, Incidente (Casi-accidente), Enfermedad Ocupacional
            
            // Descripción y Análisis
            $table->text('descripcion_relato'); // Lo que ocurrió
            $table->text('lesion_detallada')->nullable(); // Parte del cuerpo afectada
            $table->text('causas_inmediatas')->nullable(); // Actos o condiciones inseguras
            $table->text('causas_raiz')->nullable(); // Fallas de gestión/entrenamiento
            
            // Testigos y Medidas
            $table->string('testigos')->nullable();
            $table->text('acciones_correctivas'); // ¿Qué se hará para que no pase de nuevo?
            $table->date('fecha_cierre_investigacion')->nullable();

            $table->unsignedBigInteger('consulta_id')->nullable(); // Relación opcional
            // $table->foreignId('consulta_id')->nullable()->constrained('med_consultas'); // Vinculo opcional con la atención médica
            $table->foreign('consulta_id')->references('id')->on('med_consultas');
            
            $table->foreignId('user_id')->constrained('users'); // Investigador/Médico
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_accidentes');
    }
};
