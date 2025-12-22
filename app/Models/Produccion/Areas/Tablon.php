<?php

namespace App\Models\Produccion\Areas;

use App\Models\Produccion\Agro\Variedad; // Importar el nuevo modelo
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// Importar el modelo Lote
use App\Models\Produccion\Areas\Lote; 

class Tablon extends Model
{

    protected $table = 'tablones'; 

    protected $fillable = [
        'lote_id', 
        'codigo_tablon_interno', 
        'nombre', 
        'area_ha', // Renombrado de 'hectareas'
        'variedad_id', // NUEVO
        'fecha_siembra', // NUEVO
        'meta_ton_ha', // NUEVO
        'tipo_suelo', 
        'estado', 
        'descripcion'
    ];
    
    // Indica que 'fecha_siembra' debe ser tratada como fecha
    protected $casts = [
        'fecha_siembra' => 'date',
    ];

    // Método de Arranque para la lógica del código
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tablon) {
            // Buscamos el lote para obtener su código completo (ej: 0102)
            $lote = Lote::findOrFail($tablon->lote_id);
            
            // Generar el código completo: Lote_Completo + Tablon_Interno
            $tablon->codigo_completo = $lote->codigo_completo . $tablon->codigo_tablon_interno;
        });
        
        // Es buena práctica añadir el 'updating' si el código puede cambiar
        static::updating(function ($tablon) {
            if ($tablon->isDirty('lote_id') || $tablon->isDirty('codigo_tablon_interno')) {
                 $lote = Lote::findOrFail($tablon->lote_id);
                 $tablon->codigo_completo = $lote->codigo_completo . $tablon->codigo_tablon_interno;
            }
        });
    }

    // Relación: Tablon pertenece a un Lote
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class);
    }
    
    // Relación: Tablon tiene una Variedad de Caña
    public function variedad(): BelongsTo
    {
        // Se relaciona con el nuevo namespace de Modelos: Produccion/Agro
        return $this->belongsTo(Variedad::class); 
    }
}