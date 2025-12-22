<?php

namespace App\Models\Logistica\Taller;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $table = 'checklists'; 
    
    protected $fillable = [
        'orden_servicio_id', // ¡Asegúrate de que este campo esté en la migración!
        'nombre',
        'tipo_activo',
        'intervalo_referencia',
        'descripcion_tareas',
    ];

    // ------------------------------------------
    // RELACIONES
    // ------------------------------------------

    /**
     * Un Checklist siempre pertenece a una Orden de Servicio.
     * Relación: Uno a Uno (Inversa)
     */
    public function ordenServicio()
    {
        return $this->belongsTo(OrdenServicio::class, 'orden_servicio_id');
    }

    /**
     * Un Checklist tiene muchos ítems de detalle.
     */
  public function detallesChecklist()
    {
        // Esto le dice a Laravel: Busca registros en ChecklistDetalle
        // donde el campo 'orden_servicio_id' coincida con el campo 'orden_servicio_id' 
        // de este modelo Checklist.
        return $this->hasMany(ChecklistDetalle::class, 'orden_servicio_id', 'orden_servicio_id');
    }
    /**
     * Un Checklist puede estar en varias Programaciones de MP.
     */
    public function programacionesMP()
    {
        return $this->hasMany(ProgramacionMP::class, 'checklist_id');
    }
}