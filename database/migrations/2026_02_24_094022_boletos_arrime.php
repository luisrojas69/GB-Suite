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
        Schema::create('boletos_arrime', function (Blueprint $table) {
            $table->id();
            
            // Identificadores y Relaciones Principales
            $table->string('boleto', 20)->unique()->comment('Número de boleto del Central');
            $table->string('remesa', 20)->nullable();
            $table->string('cod_sector', 20)->nullable()->comment('Codigo de la Hacienda segun Central Ej: 00008-06');;
            $table->foreignId('zafra_id')->constrained('zafras')->onDelete('no action');
            $table->foreignId('tablon_id')->constrained('tablones')->onDelete('no action');
            $table->foreignId('central_id')->constrained('centrales')->onDelete('no action');
            $table->integer('dia_zafra')->nullable(); 
            
            // Datos de Logística (Opcionales, dependen de si el central los manda)
            $table->foreignId('activo_jaiba_id')->nullable()->constrained('activos')->comment('ID de la Jaiba (GBC11)');
            $table->foreignId('activo_empuje_id')->nullable()->constrained('activos')->comment('ID del Empuje/Acarreo (GBT20)');
            $table->foreignId('contratista_id')->nullable()->constrained('contratistas')->comment('Si aplica un contratista de corte');
            
            // Strings directos del archivo (No normalizados a propósito para evitar basura)
            $table->string('id_chofer', 150)->nullable();
            $table->string('chofer_nombre', 150)->nullable();
            $table->string('transporte_placa', 20)->nullable();
            
            // Indicadores Productivos Clave
            $table->decimal('toneladas_netas', 10, 3)->comment('Caña limpia procesada');
            $table->decimal('rendimiento_real', 5, 2)->nullable()->comment('Azúcar obtenida (Rdto)');
            $table->decimal('trash_porcentaje', 5, 2)->nullable()->comment('Impurezas %');
            
            // Fechas y Tiempos
            $table->dateTime('fecha_quema')->nullable();
            $table->dateTime('fecha_arrime');
            $table->decimal('ttp_horas', 6, 2)->nullable()->comment('Tiempo Transcurrido Quema-Arrime en horas');
            
            // Control de Auditoría
            $table->enum('estado', ['Borrador', 'Procesado', 'Liquidado'])->default('Procesado');
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boletos_arrime');
    }
};
