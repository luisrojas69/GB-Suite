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
        Schema::create('inv_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial')->unique()->nullable();
            $table->string('asset_tag')->unique()->nullable(); // Nro Activo

            // Para agrupar: 'IT', 'Mobiliario', 'Herramientas', etc.
            $table->string('item_group')->default('IT')->index(); 
            
            // Ruta de la imagen del equipo
            $table->string('image_path')->nullable();
            
            $table->enum('status', ['disponible', 'asignado', 'mantenimiento', 'desincorporado'])->default('disponible');
            $table->foreignId('category_id')->constrained('inv_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_items');
    }
};
