<?php

namespace App\Models\Produccion\Areas;

use App\Models\Produccion\Agro\Variedad; // Importar el nuevo modelo
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// Importar el modelo Lote
use App\Models\Produccion\Areas\Lote; 
use Illuminate\Database\Query\Expression; // Importar esto
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Tablon extends Model
{
    use HasSpatial;

    protected $table = 'tablones'; 

    protected $fillable = [
        'lote_id',
        'variedad_id',
        'codigo_tablon_interno',
        'codigo_completo',
        'nombre',
        'tipo_ciclo',
        'numero_soca',
        'fecha_inicio_ciclo',
        'meta_ton_ha', // Antes rendimiento_proyectado_ha
        'hectareas_documento',
        'tipo_suelo',
        'estado', // Antes estado_operativo
        'geometria',
        'descripcion'
    ];
    
    // Indica que 'fecha_siembra' debe ser tratada como fecha
    protected $casts = [
        'geometria' => Polygon::class,
        'fecha_inicio_ciclo' => 'date'
    ];


    // Cálculo automático de la edad en meses
    public function getEdadMesesAttribute()
    {
        if (!$this->fecha_inicio_ciclo) return 0;
        return \Carbon\Carbon::parse($this->fecha_inicio_ciclo)->diffInMonths(now());
    }

    // Relación con la última labor realizada
    public function ultimaLabor()
    {
        return $this->hasOne(RegistroLabor::class)->latestOfMany();
    }

    // Añade esto a tu modelo Tablon sin borrar nada de lo anterior
    public function historialLabores()
    {
        // Relación a través de la tabla de detalle
        return $this->belongsToMany(
            RegistroLabor::class, 
            'labor_tablon_detalle', 
            'tablon_id', 
            'registro_labor_id'
        )->withPivot('hectareas_logradas', 'variedad_id')->withTimestamps();
    }
    
    /**
     * Accesor para asegurar que la geometría se lea correctamente en SQL Server
     */
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

    // Método de Arranque para la lógica del código
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tablon) {
            // Buscamos el lote para obtener su código completo (ej: 0102)
            $lote = Lote::findOrFail($tablon->lote_id);
            
            // Generar el código completo: Lote_Completo + Tablon_Interno
            $tablon->codigo_completo = $lote->codigo_completo . $tablon->codigo_tablon_interno;
        });
        
        // Es buena práctica añadir el 'updating' si el código puede cambiar
        static::updating(function ($tablon) {
            if ($tablon->isDirty('lote_id') || $tablon->isDirty('codigo_tablon_interno')) {
                 $lote = Lote::findOrFail($tablon->lote_id);
                 $tablon->codigo_completo = $lote->codigo_completo . $tablon->codigo_tablon_interno;
            }
        });
    }

    // Relación: Tablon pertenece a un Lote
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class);
    }
    
    // Relación: Tablon tiene una Variedad de Caña
    public function variedad(): BelongsTo
    {
        // Se relaciona con el nuevo namespace de Modelos: Produccion/Agro
        return $this->belongsTo(Variedad::class); 
    }
}