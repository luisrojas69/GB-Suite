<?php

namespace App\Models\Logistica\Taller; // Ajusta el namespace si es necesario

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenRepuesto extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla de la base de datos.
     * @var string
     */
    protected $table = 'orden_repuestos'; // Nombre de la tabla según tu migración

    /**
     * Los atributos que son asignables masivamente.
     * Incluimos el costo_total para que pueda ser calculado en el controlador antes de guardar.
     * @var array<int, string>
     */
    protected $fillable = [
        'orden_servicio_id',
        'nombre_repuesto',
        'codigo_inventario',
        'cantidad_utilizada',
        'costo_unitario',
        'costo_total', 
    ];

    /**
     * Relación con la Orden de Servicio a la que pertenece esta línea.
     */
    public function ordenServicio()
    {
        // Asumo que el modelo principal de la orden es 'OrdenServicio'
        return $this->belongsTo(\App\Models\Logistica\Taller\OrdenServicio::class, 'orden_servicio_id'); 
    }
    
    // Si necesitas rastrear el repuesto original en el inventario (solo si es necesario para referencias futuras, no para costeo)
    /*
    public function repuestoInventario()
    {
        // Se relaciona con el modelo 'Repuesto' si el 'codigo_inventario' es el código primario o único.
        // O si tienes el ID, usarías belongsTo. En este caso, no tienes el foreign key ID.
        // Si tienes el modelo Repuesto y quieres buscarlo por código:
        return $this->hasOne(\App\Models\Logistica\Inventario\Repuesto::class, 'codigo', 'codigo_inventario');
    }
    */
    
    /**
     * Mutator para asegurar que el costo_total se calcule automáticamente.
     * Aunque lo tienes en $fillable, esta es una buena práctica de protección.
     * Se ejecuta al setear la 'cantidad_utilizada' o 'costo_unitario'.
     */
    protected static function booted()
    {
        static::saving(function (OrdenRepuesto $model) {
            // Recalcula el costo total antes de guardar
            $model->costo_total = $model->cantidad_utilizada * $model->costo_unitario;
        });
    }
}