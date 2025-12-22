<?php

namespace App\Models\Produccion\Areas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    protected $table = 'sectores'; // Laravel entiende sectors

    protected $fillable = ['codigo_sector', 'nombre', 'descripcion'];

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }
}