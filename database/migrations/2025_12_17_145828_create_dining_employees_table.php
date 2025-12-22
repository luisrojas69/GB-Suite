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
        // Migración
       Schema::create('dining_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('biometric_id')->unique(); // Clave para sincronizar con ZK
            $table->string('name');
            $table->string('card_number')->nullable();
            $table->string('department')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dining_employees');
    }
};
