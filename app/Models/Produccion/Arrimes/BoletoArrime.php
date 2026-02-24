<?php

namespace App\Models\Produccion\Arrimes;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produccion\Areas\Tablon;
use App\Models\Logistica\Taller\Activo;
use App\Models\Produccion\Agro\Zafra;

class BoletoArrime extends Model
{
    protected $table = 'boletos_arrime';

    protected $fillable = [
        'boleto', 'remesa', 'cod_sector', 'zafra_id', 'tablon_id', 
        'central_id', 'dia_zafra', 'activo_jaiba_id', 'activo_empuje_id', 
        'contratista_id', 'id_chofer', 'chofer_nombre', 'transporte_placa', 
        'toneladas_netas', 'rendimiento_real', 'trash_porcentaje', 
        'fecha_quema', 'fecha_arrime', 'ttp_horas', 'estado', 'observaciones'
    ];

    protected $casts = [
        'fecha_quema' => 'datetime',
        'fecha_arrime' => 'datetime',
        'toneladas_netas' => 'decimal:3',
        'rendimiento_real' => 'decimal:2',
    ];

    // Relaciones
    public function zafra() {
        return $this->belongsTo(Zafra::class);
    }

    public function tablon() {
        return $this->belongsTo(Tablon::class);
    }

    public function jaiba() {
        return $this->belongsTo(Activo::class, 'activo_jaiba_id');
    }

    public function empuje() {
        return $this->belongsTo(Activo::class, 'activo_empuje_id');
    }
}