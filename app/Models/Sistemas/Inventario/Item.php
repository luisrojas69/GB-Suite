<?php

namespace App\Models\Sistemas\Inventario;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'inv_items';
    protected $fillable = ['name', 'brand', 'model', 'serial', 'asset_tag', 'item_group', 'image_path', 'status', 'category_id'];

    // Relación con la asignación activa actual
    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)->whereNull('returned_at');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Sistemas\Inventario\Category::class, 'category_id');
    }

    public function setItemGroupAttribute($value)
    {
        $this->attributes['item_group'] = strtoupper($value);
    }

    
    
}