<?php

namespace App\Models\RRHH\Comedor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealType extends Model
{
    use HasFactory;

    protected $table = 'meal_types';

    protected $fillable = [
        'name',
        'status_code',
        'start_time',
        'end_time',
        'price',
        'is_active',
    ];

    /**
     * Relación con los registros de comedor
     */
    public function diningRecords()
    {
        // Nota: El modelo DiningRecord se creará en el siguiente paso del CRUD
        return $this->hasMany(DiningRecord::class, 'meal_type_id');
    }
}