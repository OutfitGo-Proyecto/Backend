<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function tiendas()
    {
        return $this->belongsToMany(Tienda::class, 'producto_tiendas')
                    ->using(ProductoTienda::class)
                    ->withPivot('precio', 'url', 'en_stock')
                    ->withTimestamps();
    }

    public function tallas()
    {
        return $this->belongsToMany(Talla::class, 'producto_talla');
    }

    public function colores()
    {
        return $this->belongsToMany(Color::class, 'producto_color');
    }
}