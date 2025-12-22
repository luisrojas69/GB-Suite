<?php

namespace App\Models\Produccion\Agro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variedad extends Model
{
    protected $table = 'variedades';

    protected $fillable = ['nombre', 'codigo', 'meta_pol_cana', 'descripcion'];

    /**
     * Relación: Una variedad puede estar sembrada en varios tablones.
     */
    public function tablones(): HasMany
    {
        // NOTA: Se relaciona con el Tablon en el namespace de Áreas.
        return $this->hasMany(\App\Models\Produccion\Areas\Tablon::class);
    }
}