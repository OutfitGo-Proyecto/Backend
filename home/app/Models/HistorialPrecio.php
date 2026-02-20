<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialPrecio extends Model
{
    use HasFactory;

    protected $table = 'historial_precios'; // Cambiado de price_histories

    protected $fillable = [
        'producto_id', // product_id -> producto_id
        'tienda_id',   // store_id -> tienda_id
        'precio',      // price -> precio
        'fecha_registro', // recorded_at -> fecha_registro
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'fecha_registro' => 'datetime',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'tienda_id');
    }
}
