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
        Schema::create('checklist_detalles', function (Blueprint $table) {
            $table->id();
            
            // Relación con el encabezado del Checklist
            // Asumimos que tu tabla de encabezado se llama 'checklists'
            $table->foreignId('checklist_id')
                  ->constrained('checklists') 
                  ->onDelete('cascade'); // Si se elimina el Checklist principal, se eliminan sus detalles
            
            // Contenido del Item
            $table->text('descripcion')->comment('Descripción de la tarea o ítem a inspeccionar.');
            $table->boolean('es_critico')->default(false)->comment('Indica si el ítem es obligatorio para cerrar la OS.');
            $table->integer('orden')->nullable()->comment('Orden de visualización dentro del Checklist.');
            
            // Estado y Trazabilidad (Lo más importante para la lógica de negocio)
            $table->enum('estado', ['PENDIENTE', 'COMPLETADO', 'RECHAZADO', 'N/A'])->default('PENDIENTE');
            $table->text('observaciones')->nullable()->comment('Notas o hallazgos del técnico.');
            $table->timestamp('fecha_completado')->nullable();
            
            // Quién completó/revisó el ítem
            $table->foreignId('completado_por_user_id')->nullable()->constrained('users');

            $table->timestamps();
            
            // Índice para mejorar la velocidad al cargar un checklist específico
            $table->index(['checklist_id', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_detalles');
    }
};