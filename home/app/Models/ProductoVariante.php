<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoVariante extends Model
{
    use HasFactory;

    protected $fillable = ['producto_id', 'talla_id', 'color_id', 'stock'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function talla()
    {
        return $this->belongsTo(Talla::class);
    }
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}