<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Datos de Liquidación de Calidad y Valor (Boleta del Central).
     */
    public function up(): void
    {
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->id();
            
            // Relación (Uno a Uno)
            $table->foreignId('molienda_id')->unique()->constrained('moliendas')->onDelete('cascade');
            
            // Datos de Calidad (Del PDF)
            $table->decimal('pol_cana', 8, 2)->comment('Polarización (%) en caña.');
            $table->decimal('fibra_cana', 8, 2)->comment('Fibra (%) en caña.');
            
            // Datos Financieros
            $table->decimal('precio_base', 10, 4)->comment('Precio base por tonelada de T.T.P. o azúcar.');
            $table->decimal('deducibles', 10, 2)->default(0)->comment('Deducciones por flete, calidad, etc.');
            $table->decimal('liquidacion_neta', 10, 2)->comment('Valor final neto a liquidar por este arrimo.');
            $table->date('fecha_cierre')->nullable()->comment('Fecha de cierre de la liquidación.');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidaciones');
    }
};