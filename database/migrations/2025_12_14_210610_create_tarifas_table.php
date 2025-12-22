<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * * Tabla para almacenar tarifas de referencia de Liquidación (ej: precio base, fletes).
     */
    public function up(): void
    {
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            
            $table->string('concepto', 100)->comment('Ej: Precio TTP, Precio Azúcar, Tarifa Flete');
            $table->decimal('valor', 10, 4)->comment('Valor monetario o de tasa de la tarifa.');
            $table->string('unidad', 20)->comment('Ej: USD/Ton, USD/QQ, USD/Ha, %');
            $table->date('fecha_vigencia')->comment('Fecha a partir de la cual aplica esta tarifa.');
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->text('descripcion')->nullable();
            
            $table->timestamps();
            
            // Un concepto debe ser único por fecha de vigencia para evitar duplicados en el mismo día.
            $table->unique(['concepto', 'fecha_vigencia']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifas');
    }
};