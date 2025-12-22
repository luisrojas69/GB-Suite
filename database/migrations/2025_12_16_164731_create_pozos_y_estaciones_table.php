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
        Schema::create('pozos_y_estaciones', function (Blueprint $table) {
            $table->id();
            
            // Información Básica
            $table->string('nombre')->unique();
            $table->string('ubicacion')->nullable();
            $table->string('coordenadas')->nullable();
            
            // Tipo de Activo y Subtipo
            $table->enum('tipo_activo', ['POZO', 'ESTACION_REBOMBEO']);
            $table->enum('subtipo_pozo', ['TURBINA', 'SUMERGIBLE'])->nullable();
            
            // Relación a sí misma (FK)
            $table->unsignedBigInteger('id_pozo_asociado')->nullable(); 
            
            // Control de Estatus
            $table->enum('estatus_actual', ['OPERATIVO', 'PARADO', 'EN_MANTENIMIENTO'])->default('OPERATIVO');
            $table->timestamp('fecha_ultimo_cambio')->nullable();
            
            $table->timestamps();
        });

        // DEFINICIÓN DE LA CLAVE FORÁNEA AUTORREFERENCIADA (CORRECCIÓN APLICADA AQUÍ)
        Schema::table('pozos_y_estaciones', function (Blueprint $table) {
            $table->foreign('id_pozo_asociado')
                  ->references('id')
                  ->on('pozos_y_estaciones')
                  ->onDelete('NO ACTION'); // *** SOLUCIÓN: ON DELETE NO ACTION ***
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pozos_y_estaciones', function (Blueprint $table) {
            $table->dropForeign(['id_pozo_asociado']);
        });
        Schema::dropIfExists('pozos_y_estaciones');
    }
};