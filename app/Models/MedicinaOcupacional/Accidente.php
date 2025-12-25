<?php

namespace App\Models\MedicinaOcupacional;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Accidente extends Model
{
    protected $table = 'med_accidentes';
    protected $guarded = ['id'];
    protected $casts = [
        'fecha_hora_accidente' => 'datetime',
    ];
    protected $fillable = [
        'paciente_id',
        'consulta_id',
        'user_id', // ¡Importante!
        'fecha_hora_accidente',
        'lugar_exacto',
        'tipo_evento', // ¡Importante!
        'causas_inmediatas',
        'causas_raiz', // ¡Importante!
        'descripcion_relato',
        'lesion_detallada',
        'acciones_correctivas',
        'testigos'
    ];
    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'consulta_id');
    }

    /**
     * Relación: El Accidente pertenece a un Paciente.
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    // Relación con el Investigador (Usuario del sistema)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}