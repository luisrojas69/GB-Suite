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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();

            // Identificación y Lote
            // Nota: Se usa 'string' y 'nullable' debido a valores como 'S/I' o IDs múltiples en los archivos adjuntos.
            // Para un control estricto futuro, se recomienda hacer este campo UNIQUE y NOT NULL.
            $table->string('iron_id', 50)->nullable()->index()->comment('Sello de hierro, ID o código de chapa del animal.');
            $table->string('lot', 50)->nullable()->index()->comment('Lote de agrupación del animal.');

            // Datos Maestros del Animal
            $table->enum('sex', ['Macho', 'Hembra'])->comment('Sexo del animal.');
            $table->date('birth_date')->nullable()->comment('Fecha de nacimiento (necesario para cálculo de costos y edad).');
            $table->boolean('is_active')->default(true)->comment('Estado: true (en inventario/vivo), false (vendido/muerto).');
            $table->foreignId('specie_id')->after('lot')->constrained()->onDelete('no action');
            $table->foreignId('category_id')->after('specie_id')->constrained()->onDelete('no action');
            $table->foreignId('owner_id')->after('birth_date')->constrained()->onDelete('no action');
            $table->foreignId('location_id')->after('owner_id')->constrained()->onDelete('no action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};