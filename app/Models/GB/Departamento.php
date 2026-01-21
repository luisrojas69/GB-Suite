<?php

namespace App\Models\GB;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sistemas\Inventario\Assignment;

class Departamento extends Model
{
    protected $table = 'departamentos';

    protected $fillable = [
        'nombre',
        'codigo_profit', // Por si decides sincronizar el ID de Profit después
        'descripcion'
    ];

    /**
     * Relación polimórfica: Permite ver qué equipos están asignados al departamento.
     */
    public function assignments()
    {
        return $this->morphMany(Assignment::class, 'assignable');
    }

    /**
     * Obtener el equipo que el departamento tiene actualmente (sin devolver)
     */
    public function currentAssignments()
    {
        return $this->morphMany(Assignment::class, 'assignable')->whereNull('returned_at');
    }
}