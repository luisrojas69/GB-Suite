<?php

namespace App\Models\Sistemas\Inventario;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];
    protected $table = 'inv_assignments';
    protected $fillable = [
        'item_id', 'assignable_id', 'assignable_type', 
        'location_id', 'accessories', 'assigned_at', 'returned_at', 'return_notes'
    ];

    // Esta función permite que la asignación pertenezca a un Empleado o Depto
    public function assignable()
    {
        return $this->morphTo();
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\GB\Ubicacion::class);
    }
}