<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// ...

    public function up(): void
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            
            // Clave Foránea
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
            
            $table->string('codigo_lote_interno', 5); // Ej: 02, 03
            $table->string('codigo_completo', 10)->unique(); // Ej: 0102
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->timestamps();

            // Aseguramos que no haya dos lotes con el mismo código interno DENTRO de un mismo sector
            $table->unique(['sector_id', 'codigo_lote_interno']);
        });
    }

// ...

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
