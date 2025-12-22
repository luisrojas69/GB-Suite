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
// database/migrations/XXXX_XX_XX_create_orden_servicios_table.php

        Schema::create('orden_servicios', function (Blueprint $table) {
            $table->id();
            
            // Relación con el Activo
            $table->foreignId('activo_id')->constrained('activos'); // FK a la tabla 'activos'
            
            // Tipo de Servicio y Responsables
            $table->string('codigo_orden')->unique()->comment('OS-2025-0001');
            $table->enum('tipo_servicio', ['Preventivo', 'Correctivo']);
            $table->foreignId('solicitante_id')->comment('Usuario que genera la orden (Gerente de Logística)');
            $table->string('mecanico_asignado')->nullable()->comment('Nombre del empleado que ejecuta');
            
            // Flujo de Tiempos y Status (Mejores Prácticas)
            $table->enum('status', ['Abierta', 'En Proceso', 'Cerrada', 'Cancelada'])->default('Abierta');
            $table->text('descripcion_falla')->nullable();
            $table->integer('lectura_inicial')->comment('Km u Horas al ingresar');
            $table->integer('lectura_final')->nullable()->comment('Km u Horas al salir');

            // Fechas Clave
            $table->dateTime('fecha_inicio_taller')->nullable();
            $table->dateTime('fecha_fin_trabajo')->nullable();
            $table->dateTime('fecha_salida_taller')->nullable();

            // Costos
            $table->decimal('costo_mano_obra_externa', 10, 2)->default(0);
            $table->decimal('costo_outsourcing', 10, 2)->default(0)->comment('Baqueteo, rectificación, etc.');
            $table->decimal('costo_repuestos_total', 10, 2)->default(0); // Se actualiza automáticamente
            $table->decimal('costo_total_servicio', 10, 2)->default(0); // Suma de todos los costos

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_servicios');
    }
};
