<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('registros_pluviometricos', function (Blueprint $table) {
            $table->id();
            // Relación con el sector (asumiendo que tu tabla es 'sectores')
            $table->foreignId('id_sector')->constrained('sectores')->onDelete('cascade');
            
            $table->date('fecha');
            $table->decimal('cantidad_mm', 8, 2)->default(0); // Para soportar precisión
            
            // Lo que conversamos: Intensidad y Observaciones
            $table->enum('intensidad', ['NULA', 'LIGERA', 'MODERADA', 'FUERTE', 'TORRENCIAL'])->default('NULA');
            $table->string('observaciones', 500)->nullable();
            
            // Trazabilidad
            $table->foreignId('id_usuario_registro')->constrained('users');
            $table->timestamps();

            // Índice único para evitar duplicidad: Un sector solo tiene un registro por día
            $table->unique(['id_sector', 'fecha'], 'uk_sector_fecha_pluvio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_pluviometricos');
    }
};
