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
        Schema::create('animal_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->onDelete('cascade'); // Si se elimina el animal, se eliminan los eventos
            $table->enum('event_type', ['Mortalidad', 'Venta', 'Traslado', 'Descarte'])->index();
            $table->date('event_date')->index();
            $table->string('cause', 255)->nullable(); // Solo para mortalidad
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_events');
    }
};
