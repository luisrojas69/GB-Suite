<?php

namespace App\Models\Produccion\Animales\Costos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'debit_account',
        'credit_account',
        'description_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación con todos los gastos asociados a este tipo de costo.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}