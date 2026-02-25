<?php

namespace App\Models\Produccion\Labores;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produccion\Areas\Tablon;

class LaborTablonDetalle extends Model
{
    protected $table = 'labor_tablon_detalle';
    protected $fillable = [
        'registro_labor_id', 'tablon_id', 'hectareas_logradas', 'variedad_id'
    ];

    public function tablon() { return $this->belongsTo(Tablon::class); }
    public function registro()
    {
        return $this->belongsTo(RegistroLabor::class, 'registro_labor_id');
    }
}