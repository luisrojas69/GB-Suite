<?php

namespace App\Models\Logistica\Taller;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Asumimos que la tabla users está en App\Models\User
use App\Models\Logistica\Taller\OrdenServicio; // Asumimos que la tabla users está en App\Models\User

class Activo extends Model
{
    use HasFactory;
    
    // Si tienes el modelo en un subdirectorio, Laravel infiere el nombre de la tabla como 'activos'.
    protected $table = 'activos'; // Aseguramos el nombre de la tabla

    protected $fillable = [
        'codigo',
        'nombre',
        'placa',
        'tipo',
        'marca',
        'modelo',
        'departamento_asignado',
        'estado_operativo',
        'lectura_actual',
        'unidad_medida',
        'fecha_adquisicion',
    ];

    protected $casts = [
        'fecha_adquisicion' => 'date',
    ];

    // ------------------------------------------
    // RELACIONES
    // ------------------------------------------

    /**
     * Un Activo tiene muchas Lecturas de Uso.
     * Relación: Uno a Muchos (HasMany)
     */
    public function lecturas()
    {
        // $activo->lecturas
        return $this->hasMany(LecturaActivo::class, 'activo_id');
    }

    /**
     * Un Activo puede tener varias Programaciones de Mantenimiento Preventivo (MPs).
     * Relación: Uno a Muchos (HasMany)
     */
    public function programacionesMP()
    {
        // $activo->programacionesMP
        return $this->hasMany(ProgramacionMP::class, 'activo_id');
    }

    public function servicios()
    {
        // $activo->programacionesMP
        return $this->hasMany(OrdenServicio::class, 'activo_id');
    }
    
    // ------------------------------------------
    // RELACIONES INVERSAS (Debe existir un usuario registrador para la lectura)
    // ------------------------------------------

    /**
     * Devuelve la última lectura registrada para este activo.
     * Utiliza la relación `lecturas` y la ordena.
     */
    public function ultimaLectura()
    {
        // Se asume que 'fecha_lectura' o 'created_at' determina la última
        return $this->hasOne(LecturaActivo::class, 'activo_id')->latest('fecha_lectura');
    }

    // ------------------------------------------
    // MÓDULO RRHH (Si tuvieras la relación)
    // ------------------------------------------

    /*
    // Si asignas el activo a un empleado/persona:
    public function empleadoAsignado()
    {
        return $this->belongsTo(\App\Models\Empleado::class, 'empleado_id'); 
    }
    */
}