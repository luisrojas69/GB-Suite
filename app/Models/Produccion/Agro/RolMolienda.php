<?php

namespace App\Models\Produccion\Agro;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produccion\Areas\Tablon;
use App\Models\Produccion\Agro\Variedad;
use App\Models\Produccion\Agro\Zafra; // Agregada la relación Zafra

class RolMolienda extends Model
{
    protected $table = 'plan_zafra_detalles';

    // Agregamos 'toneladas_estimadas' al fillable
    protected $fillable = [
        'zafra_id', 'tablon_id', 'variedad_id', 'clase_ciclo', 'ton_ha_estimadas',
        'area_estimada_has', 'toneladas_estimadas', 'rendimiento_esperado', 'fecha_corte_proyectada'
    ];

    public function tablon() { return $this->belongsTo(Tablon::class); }
    public function variedad() { return $this->belongsTo(Variedad::class); }
    public function zafra() { return $this->belongsTo(Zafra::class); }
}