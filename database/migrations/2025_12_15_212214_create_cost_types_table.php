<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cost_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nombre del tipo de gasto (Ej: Alimentación, Sanidad)');
            $table->string('debit_account', 15)->comment('Cuenta Contable (Profit) que recibe el DEBITO (Gasto o Inventario)');
            $table->string('credit_account', 15)->comment('Cuenta Contable (Profit) que recibe el CREDITO (Pasivo o Banco)');
            $table->text('description_template')->comment('Plantilla para la Descripcion del asiento en Profit');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Opcional: Insertar los tipos de costos iniciales con las cuentas de prueba
        DB::table('cost_types')->insert([
            [
                'name' => 'Alimentación',
                'debit_account' => '5.2.3.01.002', // Insumos de Producción Animal
                'credit_account' => '2.1.2.01.001', // CxP Proveedores
                'description_template' => 'Gasto por Alimento asociado a {ref_type} {ref_id}.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sanidad',
                'debit_account' => '5.2.3.01.002', 
                'credit_account' => '2.1.2.01.001',
                'description_template' => 'Gasto de Sanidad (Vacunas/Medicamentos) para {ref_type} {ref_id}.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_types');
    }
};