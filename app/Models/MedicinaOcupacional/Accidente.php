<?php

namespace App\Models\MedicinaOcupacional;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Accidente extends Model
{
    protected $table = 'med_accidentes';
    protected $guarded = ['id'];

    public function paciente() { return $this->belongsTo(Paciente::class); }
    public function consulta() { return $this->belongsTo(Consulta::class); }
    // Relación con el Investigador (Usuario del sistema)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}