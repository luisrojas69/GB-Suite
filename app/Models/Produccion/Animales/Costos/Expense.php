<?php

namespace App\Models\Produccion\Animales\Costos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Importaciones de modelos relacionados (Ajusta los namespaces si son diferentes)
use App\Models\Produccion\Animales\Animal; 
use App\Models\Produccion\Animales\Costos\AccountingExport; 
use App\Models\Produccion\Animales\Costos\CostType; 
use App\Models\Produccion\Animales\Location; // Usando el namespace que indicaste

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'expense_date',
        'cost_type_id',
        'reference_type',
        'reference_id',
        'amount',
        'description',
        'supplier_name',
        'document_number',
        'export_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];
    
    // --- RELACIONES EXPLICITAS (Para Eager Loading con 'with') ---

    /**
     * Relación con el tipo de costo (para obtener las cuentas contables).
     */
    public function costType(): BelongsTo
    {
        return $this->belongsTo(CostType::class);
    }
    
    /**
     * Relación con el lote de exportación (si ya ha sido exportado).
     */
    public function accountingExport(): BelongsTo
    {
        return $this->belongsTo(AccountingExport::class, 'export_id');
    }

    /**
     * Relación explícita para cargar Animals.
     */
    public function referenceAnimal(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'reference_id');
    }

    /**
     * Relación explícita para cargar Locations.
     */
    public function referenceLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'reference_id');
    }

    // --- ACCESSOR (Para obtener la referencia de forma unificada) ---

    /**
     * Accessor: Permite usar $expense->reference. 
     * Devuelve el modelo cargado (Animal o Location).
     */
    public function getReferenceAttribute()
    {
        if ($this->reference_type === 'animal') {
            // Devuelve la relación referenceAnimal si fue cargada con 'with'
            return $this->referenceAnimal; 
        } 
        
        if ($this->reference_type === 'location') {
            // Devuelve la relación referenceLocation si fue cargada con 'with'
            return $this->referenceLocation;
        }
        
        return null;
    }

    // --- ACCESSOR (Para obtener el Centro de Costo) ---

    /**
     * Accessor: Permite usar $expense->cost_center_id.
     * Implementa la política de obtener el CeCo de la ubicación actual.
     */
    public function getCostCenterIdAttribute(): ?string
    {
        // Usa el Accessor getReferenceAttribute()
        $reference = $this->reference; 
        
        if (!$reference) {
            return null;
        }

        // Si es Lote, obtiene el CeCo directamente de la ubicación
        if ($this->reference_type === 'location') {
            return $reference->cost_center_id ?? null;
        }

        // Si es Animal, obtiene el CeCo de la ubicación del animal
        if ($this->reference_type === 'animal' && method_exists($reference, 'location')) {
            // Asumiendo que el modelo Animal tiene una relación 'location' que apunta a una 'Location'
            return $reference->location->cost_center_id ?? null;
        }

        return null;
    }
}