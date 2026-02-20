<?php

namespace App\Observers;

use App\Models\HistorialPrecio;
use App\Models\ProductoTienda;

class ObserverProductoTienda
{
    /**
     * Handle the ProductoTienda "updated" event.
     */
    public function updated(ProductoTienda $productoTienda): void
    {
        // Verificar si el precio ha cambiado
        if ($productoTienda->isDirty('precio')) {
            HistorialPrecio::create([
                'producto_id' => $productoTienda->producto_id,
                'tienda_id' => $productoTienda->tienda_id,
                'precio' => $productoTienda->precio,
                'fecha_registro' => now(), // recorded_at -> fecha_registro
            ]);
        }
    }
}
