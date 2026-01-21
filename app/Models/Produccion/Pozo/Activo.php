<?php

namespace App\Models\Produccion\Pozo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// IMPORTANTE: Agregar estas líneas
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activo extends Model
{
    use HasFactory;

    protected $table = 'pozos_y_estaciones';
    
    protected $fillable = [
        'nombre',
        'ubicacion',
        'tipo_activo', 
        'subtipo_pozo', 
        'id_pozo_asociado',
        'estatus_actual', 
        'fecha_ultimo_cambio',
        'coordenadas',
    ];

    protected $casts = [
        'fecha_ultimo_cambio' => 'datetime',
    ];

    // Relaciones
    public function mantenimientos(): HasMany
    {
        return $this->hasMany(MantenimientoCorrectivo::class, 'id_activo');
    }

    /**
     * El aforo suele aplicar a los Pozos. 
     * Nota: En Eloquent se recomienda devolver la relación siempre 
     * y filtrar la lógica en el controlador o vista.
     */
    public function aforos(): HasMany
    {
        return $this->hasMany(Aforo::class, 'id_pozo');
    }

    /**
     * Obtiene el Pozo al que pertenece esta Estación de Rebombeo.
     */
    public function pozoAsociado(): BelongsTo
    {
        return $this->belongsTo(Activo::class, 'id_pozo_asociado');
    }

    /**
     * Obtiene todas las Estaciones asociadas a este Pozo.
     */
    public function estacionesAsociadas(): HasMany
    {
        return $this->hasMany(Activo::class, 'id_pozo_asociado');
    }
}