<?php

namespace App\Models\Produccion\Labores;

use Illuminate\Database\Eloquent\Model;

class LaborCritica extends Model
{
    protected $table = 'cat_labores_criticas';
    protected $fillable = ['nombre', 'dias_meta_pos_cosecha', 'reinicia_ciclo', 'requiere_maquinaria'];

    public function registros()
    {
        return $this->hasMany(RegistroLabor::class, 'labor_id');
    }
}