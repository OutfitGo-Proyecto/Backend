<?php

namespace App\Observers;

use App\Mail\ProductBackInStockMail;
use App\Models\Producto;
use Illuminate\Support\Facades\Mail;

class ProductoObserver
{
    /**
     * Handle the Producto "created" event.
     */
    public function created(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "updated" event.
     */
    public function updated(Producto $producto): void
    {
        if ($producto->wasChanged('precio')) {
            $producto->historialPrecios()->create([
                'precio' => $producto->precio
            ]);
        }

        if ($producto->wasChanged('stock') && $producto->getOriginal('stock') == 0 && $producto->stock > 0) {
            $this->notifyUsersBackInStock($producto);
        }
    }

    protected function notifyUsersBackInStock(Producto $producto)
    {
        $favorites = $producto->favorites()->with('user')->get();

        foreach ($favorites as $favorite) {
            $user = $favorite->user;
            // Mandamos a la cola usando queue()
            Mail::to($user->email)->queue(new ProductBackInStockMail($producto));
        }
    }

    /**
     * Handle the Producto "deleted" event.
     */
    public function deleted(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "restored" event.
     */
    public function restored(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "force deleted" event.
     */
    public function forceDeleted(Producto $producto): void
    {
        //
    }
}
