<?php

namespace App\Models\MedicinaOcupacional;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Consulta extends Model
{
    protected $table = 'med_consultas';
    protected $guarded = ['id'];

    public function accidente()
    {
        // El segundo parámetro es la llave foránea en la tabla med_accidentes
        return $this->hasOne(Accidente::class, 'consulta_id');
    }

    // Relación con el Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    // Relación con el Médico (Usuario del sistema)
    public function medico()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // Formatear fecha para el PDF
    public function getFechaFormateadaAttribute()
    {
        return $this->created_at->format('d/m/Y h:i A');
    }
}