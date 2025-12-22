<?php

namespace App\Models\Produccion\Pozo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activo extends Model
{
    use HasFactory;

    protected $table = 'pozos_y_estaciones';
    protected $fillable = [
        'nombre',
        'ubicacion',
        'tipo_activo', // POZO o ESTACION_REBOMBEO
        'subtipo_pozo', // TURBINA o SUMERGIBLE (nullable)
        'id_pozo_asociado', // FK para estaciones
        'estatus_actual', // OPERATIVO, PARADO, EN_MANTENIMIENTO
        'fecha_ultimo_cambio',
        'coordenadas',
        // Otros campos para caracteristicas del pozo/estacion
    ];

    protected $casts = [
        'fecha_ultimo_cambio' => 'datetime',
    ];

    // Relaciones
    public function mantenimientos()
    {
        return $this->hasMany(MantenimientoCorrectivo::class, 'id_activo');
    }

    public function aforos()
    {
        // El aforo solo aplica a los Pozos
        if ($this->tipo_activo === 'POZO') {
            return $this->hasMany(Aforo::class, 'id_pozo');
        }
        return null;
    }
}