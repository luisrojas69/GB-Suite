<?php

namespace App\Models\GB;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sistemas\Inventario\Assignment;
use App\Models\Sistemas\Inventario\Item;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones'; // Nombre de tu tabla existente
    protected $fillable = ['nombre', 'descripcion', 'codigo_sucursal'];

    /**
     * Relación con las asignaciones históricas en esta ubicación
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'location_id');
    }

    /**
     * Obtener los equipos que están ACTUALMENTE en esta ubicación
     */
    public function currentItems()
    {
        return Item::whereHas('currentAssignment', function($query) {
            $query->where('location_id', $this->id);
        })->get();
    }
}