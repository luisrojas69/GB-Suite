<?php

namespace App\Models\Logistica\Taller;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; 
// Importa todos los modelos necesarios
use App\Models\Logistica\Taller\Activo; 
use App\Models\Logistica\Taller\Checklist; 
use App\Models\Logistica\Taller\OrdenRepuesto; 


class OrdenServicio extends Model
{
    use HasFactory;

    protected $table = 'orden_servicios';
    
    // El código de orden se llena en el controlador, no aquí.
    protected $fillable = [
        'activo_id',
        'codigo_orden',
        'tipo_servicio',
        'solicitante_id',
        'mecanico_asignado',
        'status',
        'descripcion_falla',
        'lectura_inicial',
        'lectura_final',
        'fecha_inicio_taller',
        'fecha_fin_trabajo',
        'fecha_salida_taller',
        'costo_mano_obra_externa',
        'costo_outsourcing',
        'costo_repuestos_total',
        'costo_total_servicio',
    ];

    protected $casts = [
        'fecha_inicio_taller' => 'datetime',
        'fecha_fin_trabajo' => 'datetime',
        'fecha_salida_taller' => 'datetime',
    ];

    // ------------------------------------------
    // RELACIONES
    // ------------------------------------------

    public function activo()
    {
        return $this->belongsTo(Activo::class, 'activo_id');
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }
    
    /**
     * Una Orden de Servicio tiene un solo Checklist (Encabezado).
     * Relación: Uno a Uno
     */
   public function checklist()
    {
        return $this->hasOne(Checklist::class, 'orden_servicio_id');
    }
    /**
     * Relación con los Repuestos (líneas de detalle desnormalizadas).
     */
    public function repuestos()
    {
        // Asume que la tabla de repuestos es 'orden_repuestos'
        // y que la clave foránea en esa tabla es 'orden_servicio_id'.
        return $this->hasMany(OrdenRepuesto::class, 'orden_servicio_id');
    }
}