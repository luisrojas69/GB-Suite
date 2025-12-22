<?php

namespace App\Models\Produccion\Agro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    use HasFactory;

    protected $table = 'tarifas';

    protected $fillable = [
        'concepto',
        'valor',
        'unidad',
        'fecha_vigencia',
        'estado',
        'descripcion',
    ];

    protected $casts = [
        'fecha_vigencia' => 'date',
    ];
    
    // Accesor para el valor con formato monetario para USD (o la divisa principal)
    public function getValorFormateadoAttribute()
    {
        return '$ ' . number_format($this->valor, 4, ',', '.') . ' (' . $this->unidad . ')';
    }
}