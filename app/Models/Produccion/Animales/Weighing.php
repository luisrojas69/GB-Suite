<?php

namespace App\Models\Produccion\Animales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weighing extends Model
{
    use HasFactory;
    
    // Columnas que pueden ser asignadas masivamente
    protected $fillable = [
        'animal_id',
        'weight_kg',
        'weighing_date',
        'notes',
    ];

    // Casteo de atributos
    protected $casts = [
        'weighing_date' => 'date',
        // 'weight' no necesita casteo si en la migración es DECIMAL
    ];

    /**
     * Relación: Un pesaje pertenece a un Animal.
     * Esta es la relación crucial para vincular el peso al inventario.
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}