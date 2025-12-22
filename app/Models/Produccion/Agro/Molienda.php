<?php

namespace App\Models\Produccion\Agro;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Molienda extends Model
{
    protected $table = 'moliendas';
    
    protected $fillable = [
        'tablon_id', 
        'zafra_id', 
        'destino_id', 
        'contratista_id',
        'variedad_id',
        'fecha', 
        'peso_bruto', 
        'peso_tara', 
        'toneladas', 
        'brix', 
        'pol', 
        'rendimiento', 
        'numero_soca',
        'boleto_remesa',
        'conductor_nombre',
        'vehiculo_placa'
    ];
    
    protected $casts = [
        'fecha' => 'date',
    ];

    // Relaciones ----------------------------------------------------
    
    public function tablon(): BelongsTo
    {
        // Relación con el módulo de Áreas
        return $this->belongsTo(\App\Models\Produccion\Areas\Tablon::class);
    }

    public function zafra(): BelongsTo
    {
        return $this->belongsTo(Zafra::class);
    }

    public function destino(): BelongsTo
    {
        return $this->belongsTo(Destino::class);
    }
    
    public function contratista(): BelongsTo
    {
        return $this->belongsTo(Contratista::class);
    }
    
    // Relación Uno a Uno con la boleta de calidad/valor
    public function liquidacion(): HasOne
    {
        return $this->hasOne(Liquidacion::class);
    }
}