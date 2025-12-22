<?php

namespace App\Models\RRHH\Comedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiningEmployee extends Model
{
    use HasFactory;

    // Tabla asociada
    protected $table = 'dining_employees';

    // Campos asignables masivamente
    protected $fillable = [
        'biometric_id', // El ID original del equipo ZKTeco
        'name',
        'card_number',
        'department',
        'is_active',
    ];

    // Casting para asegurar tipos de datos correctos
    protected $casts = [
        'is_active' => 'boolean',
        'biometric_id' => 'integer',
    ];

    /**
     * Relación con los registros de consumo (DiningRecords).
     * Un empleado tiene muchas marcaciones de comida.
     */
    public function diningRecords()
    {
        // Vinculamos localmente mediante 'biometric_id' 
        // hacia el campo 'employee_id' de la tabla dining_records
        return $this->hasMany(DiningRecord::class, 'employee_id', 'biometric_id');
    }

    /**
     * Scope para filtrar solo empleados activos (útil en Selects o Reportes)
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Helper para identificar si el registro es un invitado
     * Según nuestro acuerdo (Códigos entre 100 y 999)
     */
    public function isGuest()
    {
        return $this->biometric_id >= 100 && $this->biometric_id <= 999;
    }
}