<?php

namespace App\Models\Produccion\Pluviometria;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produccion\Areas\Sector; // Asumiendo tu namespace de áreas
use App\Models\User;

class RegistroPluviometrico extends Model
{
    protected $table = 'registros_pluviometricos';
    
    protected $fillable = [
        'id_sector', 'fecha', 'cantidad_mm', 'intensidad', 'observaciones', 'id_usuario_registro'
    ];

    protected $casts = [
        'fecha' => 'date',
        'cantidad_mm' => 'float'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'id_sector');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario_registro');
    }
}