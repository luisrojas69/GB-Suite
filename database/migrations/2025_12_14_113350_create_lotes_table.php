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

    public function up() {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('sectores')->onDelete('cascade');
            $table->string('codigo_lote_interno', 10); // Ej: 01, 02
            $table->string('codigo_completo', 25)->unique(); // Ej: A-01 (Autogenerado)
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            
            // El lote no suele dibujarse, se calcula de sus tablones, 
            // pero dejamos el campo por si quieres definir un perímetro general.
            $table->geometry('geometria')->nullable(); 

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
