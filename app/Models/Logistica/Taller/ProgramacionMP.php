<?php

namespace App\Models\Logistica\Taller;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramacionMP extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
     protected $table = 'programacion_mps'; // Esto anula el plural automático de Laravel

    /**
     * Los atributos que son asignables masivamente (Mass Assignable).
     * Asegúrate de que estos campos existan en tu migración.
     */
    protected $fillable = [
        'activo_id',               // ID del activo al que aplica esta programación.
        'checklist_id',            // ID de la Plantilla Maestra de Checklist asociada.
        'ultimo_valor_ejecutado',       // Lectura del odómetro/horómetro en el último MP realizado.
        'proximo_valor_lectura', // La lectura futura donde se debe realizar el siguiente MP.
        'ultima_ejecucion_fecha',         // Fecha del último mantenimiento preventivo realizado.
        'proxima_fecha_mantenimiento',// Fecha futura programada para el siguiente MP.
        'status',                  // Estado de la programación (Activa, Vencida, Ejecutada).
    ];

    /**
     * Casting de tipos de datos.
     */
    protected $casts = [
        'ultimo_valor_ejecutado' => 'integer',
        'proximo_valor_lectura' => 'integer',
        'fecha_ultimo_mp' => 'date',
        'proxima_fecha_mantenimiento' => 'date',
    ];

    // --- RELACIONES ---

    /**
     * Una Programación pertenece a un Activo.
     */
    public function activo()
    {
        return $this->belongsTo(Activo::class, 'activo_id');
    }

    /**
     * Una Programación está vinculada a una Plantilla Maestra de Checklist.
     * El checklist asociado es el que tiene orden_servicio_id = NULL.
     */
    public function plantillaChecklist()
    {
        // Asumiendo que el modelo Checklist es correcto
        return $this->belongsTo(Checklist::class, 'checklist_id')->whereNull('orden_servicio_id');
    }

        public function checklist()
    {
        // Asumiendo que existe el modelo Checklist
        return $this->belongsTo(Checklist::class, 'checklist_id');
    }

    // --- MÉTODOS DE AYUDA (Opcionales) ---

    /**
     * Determina si la programación ha excedido su límite de lectura.
     */
    public function estaVencidaPorLectura()
    {
        // Se necesitaría la lectura actual del activo (activo->lectura_actual)
        // para hacer esta comparación. 
        // return $this->activo->lectura_actual >= $this->proxima_lectura_programada;
        return false; // Implementación placeholder
    }

    /**
     * Determina si la programación ha excedido su límite de tiempo.
     */
    public function estaVencidaPorTiempo()
    {
        // return now()->greaterThan($this->proxima_fecha_programada);
        return false; // Implementación placeholder
    }
}