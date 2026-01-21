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
        Schema::create('inv_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inv_items');
            
            // Relación Polimórfica (Empleado o Departamento)
            $table->unsignedBigInteger('assignable_id');
            $table->string('assignable_type'); 
            
            $table->unsignedBigInteger('location_id'); // Referencia a tu tabla de ubicaciones existente
            
            $table->text('accessories')->nullable(); // Aquí van: mouse, teclado, cargador, etc.
            $table->dateTime('assigned_at');
            $table->dateTime('returned_at')->nullable();
            $table->string('signed_document_path')->nullable(); // Ruta del PDF Snappy firmado
            // Guardamos el acta firmada de esa asignación específica
            $table->string('signed_report_path')->nullable();
            $table->text('return_notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inv_assignments');
    }
};
