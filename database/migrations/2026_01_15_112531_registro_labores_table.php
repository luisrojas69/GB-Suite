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
        Schema::create('registro_labores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zafra_id');
            $table->foreignId('labor_id')->constrained('cat_labores_criticas')->onDelete('no action');;
            $table->date('fecha_ejecucion');
            $table->enum('tipo_ejecutor', ['Propio', 'Contratista'])->default('Propio');
            $table->foreignId('contratista_id')->constrained('contratistas')->onDelete('no action');;
            $table->string('contratista_nombre')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('usuario_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_labores');
    }
};
