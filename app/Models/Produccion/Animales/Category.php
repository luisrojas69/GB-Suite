<?php

namespace App\Models\Produccion\Animales;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['species_id', 'name', 'cost_center_id'];

    // Relación con la especie
    public function species()
    {
        return $this->belongsTo(Specie::class);
    }
}