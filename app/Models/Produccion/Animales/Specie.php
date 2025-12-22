<?php

namespace App\Models\Produccion\Animales;

use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    protected $fillable = ['name'];

    // Relación con las categorías (para el mantenimiento)
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}