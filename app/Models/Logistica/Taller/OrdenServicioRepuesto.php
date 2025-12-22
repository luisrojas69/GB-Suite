<?php

namespace App\Models\Logistica\Taller; // Ajusta el namespace a tu estructura

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenServicioRepuesto extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla de la base de datos.
     * @var string
     */
    protected $table = 'orden_servicio_repuesto';

    /**
     * Los atributos que son asignables masivamente.
     * @var array<int, string>
     */
    protected $fillable = [
        'orden_servicio_id',
        'repuesto_id',
        'cantidad_utilizada',
        'costo_unitario_al_uso',
        'registrado_por_user_id',
        // 'total_linea' es una columna calculada/storedAs, no debe ser fillable
    ];

    /**
     * Relación con el Repuesto utilizado.
     */
    public function repuesto()
    {
        // Ajusta el namespace si tu modelo Repuesto está en otra ubicación
        return $this->belongsTo(\App\Models\Logistica\Taller\Repuesto::class, 'repuesto_id'); 
    }
    
    /**
     * Relación con la Orden de Servicio.
     */
    public function ordenServicio()
    {
        // Ajusta el namespace si tu modelo OrdenServicio está en otra ubicación
        return $this->belongsTo(\App\Models\Logistica\Taller\OrdenServicio::class, 'orden_servicio_id'); 
    }
    
    /**
     * Relación con el usuario que registró el uso del repuesto.
     */
    public function registradoPor()
    {
        // Asumo que el modelo de Usuario es App\Models\User
        return $this->belongsTo(\App\Models\User::class, 'registrado_por_user_id'); 
    }

}