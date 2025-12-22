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
        Schema::create('accounting_exports', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('exported_by_user_id')->constrained('users')->comment('Usuario que ejecutó la exportación');
            
            $table->date('start_date')->comment('Fecha de inicio del período de gastos exportado');
            $table->date('end_date')->comment('Fecha de fin del período de gastos exportado');
            $table->dateTime('export_date')->comment('Fecha y hora exacta de la exportación');
            
            $table->string('file_name')->unique()->comment('Nombre del archivo XML/TXT generado');
            $table->integer('total_expenses_exported')->comment('Cantidad de registros de gastos (expenses) incluidos');
            $table->integer('total_accounting_lines')->comment('Cantidad total de líneas (Débito + Crédito) en el archivo XML');
            
            $table->decimal('total_debit_amount', 12, 2)->comment('Suma total de los débitos en el archivo');
            $table->decimal('total_credit_amount', 12, 2)->comment('Suma total de los créditos en el archivo');
            
            $table->boolean('is_balanced')->default(false)->comment('Verifica si Total Débito = Total Crédito');
            $table->boolean('is_processed')->default(false)->comment('Indica si el archivo ya fue importado/procesado en Profit (opcional)');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_exports');
    }
};