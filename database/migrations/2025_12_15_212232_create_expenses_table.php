<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique()->comment('ID Único de Trazabilidad para el asiento en Profit (Campo Comentario)');
            $table->date('expense_date');
            
            // Relaciones
            $table->foreignId('cost_type_id')->constrained()->comment('Relaciona al mapeo contable');
            
            // Referencia al animal o lote
            $table->enum('reference_type', ['animal', 'location'])->comment('Define si el gasto es para un animal o un lote');
            $table->unsignedBigInteger('reference_id')->comment('ID del animal o de la ubicacion (dependiendo de reference_type)');
            
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable()->comment('Comentarios adicionales del usuario');
            
            // Datos opcionales del proveedor/documento
            $table->string('supplier_name')->nullable();
            $table->string('document_number')->nullable();
            
            // Control de Exportación
            $table->foreignId('export_id')->nullable()->constrained('accounting_exports'); // Asumiendo que accounting_exports existe
            $table->timestamps();
            
            // Índice para búsquedas rápidas en exportación
            $table->index(['expense_date', 'export_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};