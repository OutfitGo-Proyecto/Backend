<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/prueba', function () {
    return ['mensaje' => '¡Conexión exitosa con Laravel!'];
});

// Rutas Públicas de Productos
Route::get('/productos', [App\Http\Controllers\ProductoController::class, 'index']);
Route::get('/productos/{slug}', [App\Http\Controllers\ProductoController::class, 'show']);
Route::get('/productos/{id}/historial', [\App\Http\Controllers\Api\ControladorHistorialPrecio::class, 'index']);