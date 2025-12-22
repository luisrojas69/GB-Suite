<?php

namespace App\Models\Produccion\Agro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destino extends Model
{
    protected $table = 'destinos';

    protected $fillable = ['nombre', 'codigo'];

    /**
     * Relación: Un destino recibe muchas moliendas.
     */
    public function moliendas(): HasMany
    {
        return $this->hasMany(Molienda::class);
    }
}