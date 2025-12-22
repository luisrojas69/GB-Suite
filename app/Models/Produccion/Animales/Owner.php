<?php

namespace App\Models\Produccion\Animales;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = ['name'];

    // Relación con los animales (opcional, para reportes)
    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}