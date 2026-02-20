<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\ProductoTienda;
use App\Observers\ObserverProductoTienda;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ProductoTienda::observe(ObserverProductoTienda::class);
    }
}
