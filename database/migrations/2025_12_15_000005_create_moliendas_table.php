<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registro de Molienda (Arrimo) de Caña.
     */
    public function up(): void
    {
        Schema::create('moliendas', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('tablon_id')->constrained('tablones'); // Origen de la caña
            $table->foreignId('zafra_id')->constrained('zafras'); // Campaña
            $table->foreignId('destino_id')->constrained('destinos'); // Central/Destino del arrimo
            $table->foreignId('variedad_id')->constrained('variedades'); // Central/Destino del arrimo
            $table->foreignId('contratista_id')->nullable()->constrained('contratistas')->onDelete('set null'); // Contratista (puede ser NULL)
            
            // Datos de la Transacción
            $table->date('fecha');
            $table->decimal('peso_bruto', 10, 2)->comment('Peso Bruto en Toneladas.');
            $table->decimal('peso_tara', 10, 2)->comment('Peso Tara Toneladas.');
            $table->decimal('toneladas', 10, 2)->comment('Peso Neto en Toneladas.');
            $table->decimal('brix', 10, 2)->comment('Grados Brix.');
            $table->decimal('pol', 10, 2)->comment('Porcentaje de Polarización.');
            $table->decimal('rendimiento', 10, 2)->comment('Toneladas de caña por hectárea - TCH');
            $table->integer('numero_soca')->comment('Ciclo de cosecha: 1=Caña Planta, 2=Soca 1, 3=Soca 2, etc.');
            $table->string('boleto_remesa', 20)->unique()->nullable()->comment('Número de boleto o remesa para trazabilidad.');
            
            // Datos Logísticos (Del Borrador)
            $table->string('conductor_nombre', 150)->nullable();
            $table->string('vehiculo_placa', 15)->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moliendas');
    }
};