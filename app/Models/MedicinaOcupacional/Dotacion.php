<?php

namespace App\Models\MedicinaOcupacional;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Importamos el modelo User

class Dotacion extends Model
{
    protected $table = 'med_dotaciones';
    protected $guarded = ['id'];

    // Relación con el Paciente (Trabajador)
    public function paciente() 
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    // RELACIÓN CON EL USUARIO (Quién registró la entrega)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Accesor para formatear la fecha de entrega automáticamente
     * Uso: $dotacion->fecha_formateada
     */
    public function getFechaFormateadaAttribute()
    {
        return \Carbon\Carbon::parse($this->fecha_entrega)->format('d/m/Y');
    }
}