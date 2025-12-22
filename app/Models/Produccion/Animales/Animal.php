<?php

namespace App\Models\Produccion\Animales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;
    
    // Columnas que pueden ser asignadas masivamente
    protected $fillable = [
        'iron_id',
        'lot',
        'sex',
        'birth_date',
        'is_active',
        'specie_id',
        'category_id',
        'owner_id',
        'location_id',
    ];

    // Casteo de fechas
    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relaciones con Tablas Maestras
    public function specie()
    {
        return $this->belongsTo(Specie::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // Relaciones Transaccionales (para referencia en reportes/detalles)
    public function weighings()
    {
        return $this->hasMany(Weighing::class);
    }

    public function events()
    {
        return $this->hasMany(AnimalEvent::class);
    }
}