<?php

namespace App\Models\Sistemas\Inventario;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'inv_categories';
    protected $fillable = ['nombre', 'descripcion'];

    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}