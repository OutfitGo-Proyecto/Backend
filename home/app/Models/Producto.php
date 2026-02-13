<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;
}

public function tiendas()
{
    return $this->belongsToMany(Tienda::class, 'producto_tiendas')
                ->withPivot('precio', 'url', 'en_stock')
                ->withTimestamps();
}