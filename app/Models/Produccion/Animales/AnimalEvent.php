<?php

namespace App\Models\Produccion\Animales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalEvent extends Model
{
    use HasFactory;
    
    // Columnas que pueden ser asignadas masivamente
    protected $fillable = [
        'animal_id',
        'event_type', // Ej: Mortalidad, Venta, Traslado, Descarte
        'event_date',
        'cause',      // Causa específica de la baja (ej: enfermedad, nombre del comprador)
        'notes',
    ];

    // Casteo de atributos
    protected $casts = [
        'event_date' => 'date',
    ];

    // Relación: Un evento pertenece a un animal
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}