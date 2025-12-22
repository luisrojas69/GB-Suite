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
        Schema::create('aforos', function (Blueprint $table) {
            $table->id();
            
            // Clave Foránea a la nueva tabla 'pozos_y_estaciones'
            $table->foreignId('id_pozo')
                  ->constrained('pozos_y_estaciones') 
                  ->onDelete('cascade');
            
            // Datos de la Medición
            $table->date('fecha_medicion');
            $table->float('caudal_medido_lts_seg'); 
            $table->float('nivel_estatico')->nullable(); 
            $table->float('nivel_dinamico')->nullable(); 
            $table->string('observaciones', 500)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aforos');
    }
};