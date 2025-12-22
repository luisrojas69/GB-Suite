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
        Schema::create('orden_repuestos', function (Blueprint $table) {
            $table->id();
            
            // Relación con la Orden de Servicio
            $table->foreignId('orden_servicio_id')->constrained('orden_servicios')->onDelete('cascade');
            
            // Identificación y Costo del Repuesto
            $table->string('nombre_repuesto');
            $table->string('codigo_inventario')->nullable()->comment('Si se sincroniza con Profit, es el código del ítem');
            $table->decimal('cantidad_utilizada', 8, 2);
            $table->decimal('costo_unitario', 10, 2)->comment('Costo Promedio del Almacén Central');
            $table->decimal('costo_total', 10, 2); // Cantidad * Costo Unitario

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_repuestos');
    }
};
