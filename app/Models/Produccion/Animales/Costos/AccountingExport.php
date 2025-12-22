<?php

namespace App\Models\Produccion\Animales\Costos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'exported_by_user_id',
        'start_date',
        'end_date',
        'export_date',
        'file_name',
        'total_expenses_exported',
        'total_accounting_lines',
        'total_debit_amount',
        'total_credit_amount',
        'is_balanced',
        'is_processed',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'export_date' => 'datetime',
        'is_balanced' => 'boolean',
        'is_processed' => 'boolean',
    ];

    /**
     * Relación con el usuario que realizó la exportación.
     */
    public function exportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exported_by_user_id');
    }

    /**
     * Relación con los gastos que forman parte de este lote de exportación.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'export_id');
    }
}