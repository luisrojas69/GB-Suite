<?php

namespace App\Models\Produccion\Agro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contratista extends Model
{
    protected $table = 'contratistas';

    protected $fillable = ['nombre', 'rif', 'contacto'];

    /**
     * Relación: Un contratista puede realizar múltiples moliendas.
     */
    public function moliendas(): HasMany
    {
        return $this->hasMany(Molienda::class);
    }
}