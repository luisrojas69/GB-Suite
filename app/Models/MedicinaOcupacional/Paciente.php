<?php

namespace App\Models\MedicinaOcupacional;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Model
{
    use SoftDeletes;
    
    protected $table = 'med_pacientes';
    protected $guarded = ['id'];

    // Para mostrar el avatar por defecto si no tiene foto
    public function getFotoAttribute($value)
    {
        if (!$value) {
            return $this->sexo == 'F' ? 'assets/img/avatar_female.png' : 'assets/img/avatar_male.png';
        }
        return 'storage/' . $value;
    }

    public function dotaciones()
    {
        return $this->hasMany(Dotacion::class);
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    public function accidentes()
    {
        return $this->hasMany(Accidente::class);
    }

}