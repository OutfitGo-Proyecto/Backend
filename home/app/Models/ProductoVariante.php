<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoVariante extends Model
{
    use HasFactory;

    protected $fillable = ['producto_id', 'talla_id', 'color_id', 'stock'];

    // Relación: Una variante pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación: Una variante tiene una talla
    public function talla()
    {
        return $this->belongsTo(Talla::class);
    }

    // Relación: Una variante tiene un color
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}