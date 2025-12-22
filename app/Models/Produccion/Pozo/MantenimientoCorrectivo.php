<?php

namespace App\Models\Produccion\Pozo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MantenimientoCorrectivo extends Model
{
    use HasFactory;

    protected $table = 'mantenimientos_correctivos';
    protected $fillable = [
        'id_activo',
        'fecha_falla_reportada',
        'sintoma_falla',
        'trabajo_realizado',
        'fecha_reinicio_operacion',
        'costo_asociado',
        'responsable',
        'tiempo_parada_horas', // Calculado en el Controller
    ];

    protected $casts = [
        'fecha_falla_reportada' => 'datetime',
        'fecha_reinicio_operacion' => 'datetime',
        'costo_asociado' => 'float',
    ];

    // Relación
    public function activo()
    {
        return $this->belongsTo(Activo::class, 'id_activo');
    }
}