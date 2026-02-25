<?php

namespace App\Models\Produccion\Arrimes;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produccion\Agro\Zafra;
use App\Models\Produccion\Areas\Tablon;

class MoliendaEjecutada extends Model
{
    protected $table = 'molienda_ejecutada';

    protected $fillable = [
        'zafra_id', 'tablon_id', 'toneladas_reales', 'rendimiento_real_avg', 
        'area_cosechada_real', 'fecha_inicio_cosecha', 'fecha_fin_cosecha', 'estado_cosecha'
    ];

    // Relaciones para los reportes
    public function zafra() { return $this->belongsTo(Zafra::class); }
    public function tablon() { return $this->belongsTo(Tablon::class); }

    /**
     * Scope para facilitar la comparación Plan vs Real en el controlador
     */
    public function scopeFinalizados($query) {
        return $query->where('estado_cosecha', 'Finalizado');
    }
}