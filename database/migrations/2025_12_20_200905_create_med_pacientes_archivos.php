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
        Schema::create('med_paciente_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('med_pacientes')->onDelete('cascade');
            $table->string('nombre_archivo'); // Nombre legible: "Audiometría 2025"
            $table->string('ruta_archivo');   // Ruta en el servidor/storage
            $table->string('tipo_archivo');   // pdf, jpg, etc.
            $table->foreignId('orden_id')->nullable()->constrained('med_ordenes_examenes');
            $table->foreignId('user_id')->constrained('users'); // Quién lo subió
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_pacientes_archivos');
    }
};
