<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Desayuno, Almuerzo, Cena
            $table->integer('status_code')->unique(); // 0, 1, 2 (ZK Status)
            $table->time('start_time'); // 06:00, 12:00, 14:00
            $table->time('end_time');   // 10:00, 14:00, 23:59
            $table->decimal('price', 8, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_types');
    }
};