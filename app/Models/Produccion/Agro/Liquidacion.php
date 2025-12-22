<?php

namespace App\Models\Produccion\Agro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Produccion\Agro\Molienda; // Asumo esta ruta para el modelo Molienda

class Liquidacion extends Model
{
    use HasFactory;

    protected $table = 'liquidaciones';

    protected $fillable = [
        'molienda_id',
        'pol_cana',
        'fibra_cana',
        'precio_base',
        'deducibles',
        'liquidacion_neta',
        'fecha_cierre',
    ];

    protected $casts = [
        'fecha_cierre' => 'date',
    ];

    /**
     * Relación: Una liquidación pertenece a un registro de Molienda (1 a 1).
     */
    public function molienda(): BelongsTo
    {
        return $this->belongsTo(Molienda::class);
    }

    /**
     * Accesor para formatear el valor de liquidación.
     */
    public function getLiquidacionNetaAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }
}