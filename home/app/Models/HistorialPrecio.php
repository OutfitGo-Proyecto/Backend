<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialPrecio extends Model
{
    protected $fillable = ['producto_id', 'precio'];

    public function producto() {
        return $this->belongsTo(Producto::class);
    }
}
