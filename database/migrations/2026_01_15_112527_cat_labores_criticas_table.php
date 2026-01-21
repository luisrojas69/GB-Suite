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
        Schema::create('cat_labores_criticas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('dias_meta_pos_cosecha'); // 15, 30, 45, 60
            $table->boolean('reinicia_ciclo')->default(false); // TRUE para "Cosecha" o "Siembra"
            $table->boolean('requiere_maquinaria')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_labores_criticas');
    }
};
