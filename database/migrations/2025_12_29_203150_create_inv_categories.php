<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inv_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Laptop, Tablet, etc.
            $table->string('modulo')->default('GENERAL');
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });

        // Insertar categorías base de una vez
        DB::table('inv_categories')->insert([
            ['nombre' => 'Laptop', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Servidores', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Desktop', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Tablet/Telefonos', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Impresoras', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Monitores', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Proyectores', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Switches/Routers', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Antenas', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Biometricos', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'DVRs/Camaras', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'UPSs/Reguladores', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Perifericos', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'IT Redes', 'modulo' => 'IT', 'created_at' => now()],
            ['nombre' => 'Audio/Sonido', 'modulo' => 'IT', 'created_at' => now()],

            // Categorías para Administración
            ['nombre' => 'Aires Acondicionados', 'modulo' => 'ADMIN', 'created_at' => now()],
            ['nombre' => 'Escritorios', 'modulo' => 'ADMIN', 'created_at' => now()],
            ['nombre' => 'Sillas de Oficina', 'modulo' => 'ADMIN', 'created_at' => now()],
            ['nombre' => 'Neveras/Refrigeración', 'modulo' => 'ADMIN', 'created_at' => now()],
            ['nombre' => 'Microondas', 'modulo' => 'ADMIN', 'created_at' => now()],
            ['nombre' => 'Otros Activos', 'modulo' => 'ADMIN', 'created_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('inv_categories');
    }
};