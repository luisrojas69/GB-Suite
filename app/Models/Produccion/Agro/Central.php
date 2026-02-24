<?php

namespace App\Models\Produccion\Agro;

use Illuminate\Database\Eloquent\Model;

class Central extends Model
{
    protected $table = 'centrales';

    protected $fillable = ['nombre', 'rif', 'ubicacion', 'activo'];
    
}
