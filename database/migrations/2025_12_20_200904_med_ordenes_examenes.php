<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('med_ordenes_examenes', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('consulta_id')->constrained('med_consultas')->onDelete('cascade');
            $table->foreignId('paciente_id')->constrained('med_pacientes');
            $table->foreignId('user_id')->constrained('users'); // Médico que ordena
            
            // Datos de la orden
            $table->json('examenes'); // Guardaremos el array de nombres de exámenes
            $table->text('observaciones')->nullable();
            
            // Estados de la orden
            $table->enum('status_orden', [
                'Pendiente', 
                'Parcial', 
                'Completada', 
                'Vencida'
            ])->default('Pendiente');
            $table->enum('interpretacion', ['Normal', 'Alterado'])->nullable();
            $table->text('hallazgos')->nullable();
            $table->string('archivo_adjunto')->nullable(); // Ruta del PDF

            $table->timestamps();
            $table->softDeletes(); // Siempre es bueno en salud laborar
        });
    }

    public function down()
    {
        Schema::dropIfExists('med_ordenes_examenes');
    }
};