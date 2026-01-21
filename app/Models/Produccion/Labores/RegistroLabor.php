<?php

namespace App\Models\Produccion\Labores;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produccion\Areas\Tablon;

class RegistroLabor extends Model
{
    protected $table = 'registro_labores';
    protected $fillable = ['labor_id', 'fecha_ejecucion', 'tipo_ejecutor', 'contratista_nombre', 'observaciones'];

    public function tablones()
    {
        return $this->belongsToMany(
            \App\Models\Produccion\Areas\Tablon::class, 
            'labor_tablon_detalle', 
            'registro_labor_id', 
            'tablon_id'
        )->withPivot('hectareas_logradas', 'variedad_id');
    }

     protected $casts = [
        'fecha_ejecucion' => 'date'
    ];

    public function labor()
    {
        return $this->belongsTo(LaborCritica::class, 'labor_id');
    }

    // 3. Relación con los detalles de maquinaria
    public function maquinarias() 
    { 
        return $this->hasMany(LaborMaquinariaDetalle::class, 'registro_labor_id'); 
    }

    // 4. Relación con los detalles de tablón (si necesitas acceder a la tabla detalle pura)
    public function detallesTablon() 
    { 
        return $this->hasMany(LaborTablonDetalle::class, 'registro_labor_id'); 
    }

    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'usuario_id');
    }
}