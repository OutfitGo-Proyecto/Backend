<?php

use App\Http\Controllers\ProductoController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Ruta para la página de inicio / catálogo / buscador
Route::get('/', [ProductoController::class, 'index'])->name('home');

// Ruta alternativa si quieres una url tipo /catalogo
Route::get('/catalogo', [ProductoController::class, 'index'])->name('productos.index');

// Ruta para ver el detalle de un producto (Comparador)
// Ejemplo: outfitgo.com/producto/zapatillas-nike-air
Route::get('/producto/{slug}', [ProductoController::class, 'show'])->name('productos.show');