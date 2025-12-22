<?php

namespace App\Models\Logistica\Taller;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; 

class LecturaActivo extends Model
{
    use HasFactory;
    
    protected $table = 'lectura_activos'; 
    
    protected $fillable = [
        'activo_id',
        'fecha_lectura',
        'valor_lectura',
        'unidad_medida',
        'registrador_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_lectura' => 'date',
    ];

    // ------------------------------------------
    // RELACIONES
    // ------------------------------------------

    /**
     * Una lectura pertenece a un Activo.
     */
    public function activo()
    {
        return $this->belongsTo(Activo::class, 'activo_id');
    }

    /**
     * Una lectura fue registrada por un Usuario (Registrador).
     */
    public function registrador()
    {
        return $this->belongsTo(User::class, 'registrador_id');
    }
}