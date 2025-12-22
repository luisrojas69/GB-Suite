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
        Schema::create('lectura_activos', function (Blueprint $table) {
            $table->id();
            
            // Relación con el Activo
            $table->foreignId('activo_id')->constrained('activos')->onDelete('cascade');
            
            // Datos de la Lectura
            $table->date('fecha_lectura');
            $table->integer('valor_lectura');
            $table->string('unidad_medida', 3); // KM o HRS (copia del Activo)
            $table->foreignId('registrador_id')->comment('Usuario que cargó la lectura (Jefe de Taller)');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectura_activos');
    }
};
