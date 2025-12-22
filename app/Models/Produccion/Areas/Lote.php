<?php

namespace App\Models\Produccion\Areas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lote extends Model
{
    protected $fillable = ['sector_id', 'codigo_lote_interno', 'nombre', 'descripcion'];
    
    // Método de Arranque para la lógica del código
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lote) {
            // Buscamos el sector para obtener su código
            $sector = Sector::findOrFail($lote->sector_id);
            
            // Generar el código completo: Sector + Lote_Interno
            $lote->codigo_completo = $sector->codigo_sector . $lote->codigo_lote_interno;
        });
    }

    // Relación: Lote pertenece a un Sector
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    // Relación: Lote tiene muchos Tablones
    public function tablones(): HasMany
    {
        return $this->hasMany(Tablon::class);
    }
}