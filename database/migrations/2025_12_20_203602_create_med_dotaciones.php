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
        Schema::create('med_dotaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('med_pacientes');
            $table->date('fecha_entrega');
            $table->string('motivo'); // Ej: Dotación Semestral, Reposición por Daño, Ingreso
            
            // Equipos entregados (Podemos usar JSON o campos fijos según tu preferencia)
            $table->string('calzado_talla')->nullable();
            $table->boolean('calzado_entregado')->default(false);
            
            $table->boolean('entregado_en_almacen')->default(false);
            $table->date('fecha_despacho_almacen')->nullable();

            $table->string('pantalon_talla')->nullable();
            $table->boolean('pantalon_entregado')->default(false);
            
            $table->string('camisa_talla')->nullable();
            $table->boolean('camisa_entregado')->default(false);
            
            $table->text('otros_epp')->nullable(); // Ej: Casco, Lentes, Guantes
            $table->text('observaciones')->nullable();

            $table->longText('firma_digital')->nullable(); // Guardará el trazo de la firma
            $table->string('qr_token')->unique(); // Para validar el ticket después
            
            $table->foreignId('user_id')->constrained('users'); // Quién entrega
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('med_dotaciones');
    }
};
