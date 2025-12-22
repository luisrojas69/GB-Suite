<?php

namespace App\Models\Logistica\Taller; // Ajusta el namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistDetalle extends Model
{
    use HasFactory;



// 1. Nombre de tabla real (de tu migración)
    protected $table = 'orden_checklist_detalles'; 

    // 2. Definición del $fillable con los nombres de columna REALES
    protected $fillable = [
        'orden_servicio_id', // Clave foránea real en esta tabla
        'tarea',             // Campo real de la tarea (antes 'descripcion')
        'completado',        // Campo real para el estado (antes 'estado')
        'notas_resultado',
    ];

    
    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    /**
     * Relación con el usuario que completó el ítem.
     */
    public function completadoPor()
    {
        return $this->belongsTo(\App\Models\User::class, 'completado_por_user_id');
    }
    
    /**
     * Scope para obtener solo los ítems que están pendientes.
     */
    public function scopePendiente($query)
    {
        return $query->where('estado', 'PENDIENTE');
    }
    
    /**
     * Scope para obtener solo los ítems críticos pendientes.
     */
    public function scopeCriticoPendiente($query)
    {
        return $query->where('es_critico', true)->where('estado', 'PENDIENTE');
    }
}