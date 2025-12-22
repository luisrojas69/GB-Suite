<?php

namespace App\Models\Produccion\Pozo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aforo extends Model
{
    use HasFactory;

    protected $table = 'aforos';
    protected $fillable = [
        'id_pozo',
        'fecha_medicion',
        'caudal_medido_lts_seg', // Usamos L/s para estandarizar
        'nivel_estatico',
        'nivel_dinamico',
        'observaciones',
    ];

    protected $casts = [
        'fecha_medicion' => 'date',
        'caudal_medido_lts_seg' => 'float',
        'nivel_estatico' => 'float',
        'nivel_dinamico' => 'float',
    ];

    // Relación
    public function pozo()
    {
        return $this->belongsTo(Activo::class, 'id_pozo');
    }
}