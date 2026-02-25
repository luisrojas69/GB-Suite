<?php

namespace App\Models\Produccion\Labores;

use Illuminate\Database\Eloquent\Model;
use App\Models\Logistica\Taller\Activo;
use App\Models\MedicinaOcupacional\Paciente;

class LaborMaquinariaDetalle extends Model
{
    protected $table = 'labor_maquinaria_detalle';
    protected $fillable = [
        'registro_labor_id', 'activo_id', 'operador_id', 
        'horometro_inicial', 'horometro_final', 'horas_desfase_uso'
    ];

    public function activo() { return $this->belongsTo(Activo::class); }
    public function operador() { return $this->belongsTo(Paciente::class, 'operador_id'); }
    public function registro()
    {
        return $this->belongsTo(RegistroLabor::class, 'registro_labor_id');
    }
}