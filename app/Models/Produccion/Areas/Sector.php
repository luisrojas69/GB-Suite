<?php

namespace App\Models\Produccion\Areas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Illuminate\Database\Query\Expression; // Importar esto
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use App\Models\Produccion\Pluviometria\RegistroPluviometrico;

class Sector extends Model
{
    use HasSpatial;

    protected $table = 'sectores';
    protected $fillable = ['codigo_sector', 'nombre', 'descripcion', 'geometria'];

    protected $casts = [
        'geometria' => Polygon::class, // Convierte el binario a objeto Polygon
    ];


    public function getGeometriaAttribute($value)
    {
        if (is_null($value)) return null;

        // Si es una expresión de DB (como la que enviamos en el update), 
        // retornamos el valor tal cual para que Eloquent no intente castearlo.
        if ($value instanceof \Illuminate\Database\Query\Expression) {
            return $value;
        }

        // Si ya es un objeto Polygon (por el cast de la librería), retornarlo.
        if ($value instanceof \MatanYadaev\EloquentSpatial\Objects\Polygon) {
            return $value;
        }

        return $value;
    }
    
    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }

    public function pluviometrias(): HasMany
    {
        return $this->hasMany(RegistroPluviometrico::class, 'id_sector');
    }

    // Relación para obtener la última lluvia registrada
    public function ultimaLluvia()
    {
        return $this->hasOne(RegistroPluviometrico::class, 'id_sector')
                    ->where('cantidad_mm', '>', 0)
                    ->latest('fecha');
    }

    public function tablones(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Tablon::class,
            Lote::class,
            'sector_id', // Llave foránea en Lotes
            'lote_id',   // Llave foránea en Tablones
            'id',        // Llave local en Sectores
            'id'         // Llave local en Lotes
        );
    }
}