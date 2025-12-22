<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dining_records', function (Blueprint $table) {
            $table->id();
            // ID del empleado o invitado (viene del ZK)
            $table->unsignedBigInteger('employee_id'); 
            
            // Relación con el tipo de comida (Desayuno, Almuerzo, Cena)
            $table->foreignId('meal_type_id')->constrained('meal_types');
            
            // Datos de la marcación
            $table->datetime('punch_time'); // Fecha y hora exacta del biométrico
            $table->integer('status_code'); // Código de estado (0, 1, 2) capturado
            
            // Auditoría y Control
            $table->decimal('cost', 8, 2); // Precio al momento de la marcación (evita cambios históricos)
            $table->string('source')->default('biometric'); // biometric, manual, exception
            $table->text('observation')->nullable();
            
            $table->timestamps();

            // Índice para acelerar la búsqueda de duplicados (Anti-Passback)
            $table->index(['employee_id', 'meal_type_id', 'punch_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dining_records');
    }
};