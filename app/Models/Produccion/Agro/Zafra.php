<?php

namespace App\Models\Produccion\Agro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zafra extends Model
{
    protected $table = 'zafras';

    protected $fillable = ['nombre', 'anio_inicio', 'anio_fin', 'fecha_inicio', 'fecha_fin', 'estado'];
    
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Relación: Una zafra contiene muchas moliendas.
     */
    public function moliendas(): HasMany
    {
        return $this->hasMany(Molienda::class);
    }
}