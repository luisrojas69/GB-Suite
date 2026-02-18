<?php

namespace App\Models\MedicinaOcupacional;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OrdenExamen extends Model
{   
    protected $table = 'med_ordenes_examenes';
    protected $fillable = [
        'consulta_id', 'paciente_id', 'user_id', 'examenes', 'observaciones', 'status_orden', 'interpretacion', 'hallazgos', 'archivo_adjunto'
    ];

    protected $casts = [
        'examenes' => 'array', // ¡Mágicamente convierte el JSON a Array!
        'created_at' => 'datetime',
    ];

    // Relaciones
    public function paciente() { return $this->belongsTo(Paciente::class); }
    public function medico() {  return $this->belongsTo(User::class, 'user_id'); }
    public function consulta() { return $this->belongsTo(Consulta::class); }
}
