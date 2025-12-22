<?php

namespace App\Models\Produccion\Animales;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'cost_center_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relación con los animales (para poder saber cuántos hay)
    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}