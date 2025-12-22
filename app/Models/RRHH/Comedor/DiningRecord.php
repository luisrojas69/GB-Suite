<?php

namespace App\Models\RRHH\Comedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiningRecord extends Model
{
    use HasFactory;

    protected $table = 'dining_records';

    protected $fillable = [
        'employee_id',
        'meal_type_id',
        'punch_time',
        'status_code',
        'cost',
        'source',
        'observation'
    ];

    protected $casts = [
        'punch_time' => 'datetime',
    ];

    /**
     * Relación con el tipo de comida
     */
    public function mealType()
    {
        return $this->belongsTo(MealType::class, 'meal_type_id');
    }

    /**
     * Obtener el nombre del empleado desde la tabla de empleados (si existe en tu DB local)
     * Asumiendo que tienes una tabla de empleados vinculada por el ID del biométrico
     */

    public function employee()
    {
        // Relacionamos el employee_id del registro con el biometric_id de la tabla de empleados
        return $this->belongsTo(DiningEmployee::class, 'employee_id', 'biometric_id');
    }


    public function diningEmployee()
    {
        return $this->belongsTo(DiningEmployee::class, 'employee_id', 'biometric_id');
    }
    
}