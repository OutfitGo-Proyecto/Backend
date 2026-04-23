<?php

namespace App\Observers;

use App\Models\ProductoVariante;
use App\Notifications\LowStockAlert;
use Illuminate\Support\Facades\Notification;

class ProductoVarianteObserver
{
    /**
     * Handle the ProductoVariante "created" event.
     */
    public function created(ProductoVariante $productoVariante): void
    {
        //
    }

    /**
     * Handle the ProductoVariante "updated" event.
     */
    public function updated(ProductoVariante $productoVariante): void
    {
        if ($productoVariante->isDirty('stock')) {
            $oldStock = $productoVariante->getOriginal('stock');
            $newStock = $productoVariante->stock;
            
            // Verificamos si el stock bajó a menos de 5 unidades
            if ($oldStock >= 5 && $newStock < 5) {
                Notification::route('mail', 'outfitgotfg@gmail.com')
                    ->notify(new LowStockAlert($productoVariante));
            }
        }
    }

    /**
     * Handle the ProductoVariante "deleted" event.
     */
    public function deleted(ProductoVariante $productoVariante): void
    {
        //
    }

    /**
     * Handle the ProductoVariante "restored" event.
     */
    public function restored(ProductoVariante $productoVariante): void
    {
        //
    }

    /**
     * Handle the ProductoVariante "force deleted" event.
     */
    public function forceDeleted(ProductoVariante $productoVariante): void
    {
        //
    }
}
