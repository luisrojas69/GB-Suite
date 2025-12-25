<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedPacientesTable extends Migration
{
    public function up()
    {
        Schema::create('med_pacientes', function (Blueprint $table) {
            $table->id();
            // Identificación (Desde Profit Nomina)
            $table->string('cod_emp', 20)->unique(); // Ficha
            $table->string('ci', 20);
            $table->string('nombre_completo');
            $table->char('sexo', 1);
            $table->date('fecha_nac')->nullable();
            $table->string('edo_civ', 10)->nullable();
            
            // Datos Laborales
            $table->date('fecha_ing')->nullable();
            $table->string('co_depart', 20);
            $table->string('des_depart');
            $table->string('co_cargo', 20);
            $table->string('des_cargo');
            $table->char('status', 2); // A: Activo, V: Vacaciones, L: Liquidado
            $table->string('co_ubicacion', 20)->nullable();

            // Salud y Discapacidad (Datos Profit Nativos)
            $table->boolean('discapacitado')->default(false);
            $table->string('tipo_discapac')->nullable();
            $table->string('co_cert', 50)->nullable(); // Carnet discapacidad
            $table->date('fecha_venc_cert')->nullable();

            // Datos Extendidos GB Suite (Editables por el médico)
            $table->string('tipo_sangre', 5)->nullable();
            $table->float('peso_inicial')->nullable();
            $table->float('estatura')->nullable();
            $table->boolean('es_zurdo')->default(false);
            $table->text('alergias')->nullable();
            $table->date('fecha_retorno_vacaciones')->nullable();
            $table->text('enfermedades_base')->nullable();
            $table->string('foto_path')->nullable();

            // Tallas (Extraídas de Fichas Profit)
            $table->string('talla_camisa', 10)->nullable();
            $table->string('talla_pantalon', 10)->nullable();
            $table->string('talla_calzado', 10)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down() { Schema::dropIfExists('med_pacientes'); }
}