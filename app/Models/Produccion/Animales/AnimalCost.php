<?php

namespace App\Models\Produccion\Animales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'period_date',
        'cost_center_id',
        'total_accumulated_expense',
        'active_animal_count',
        'unit_cost',
    ];

    protected $casts = [
        'period_date' => 'date',
        'total_accumulated_expense' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'active_animal_count' => 'integer',
    ];

    /**
     * Relación inversa con el animal.
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}