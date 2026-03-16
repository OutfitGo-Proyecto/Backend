<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\ProductoController;

// Rutas Públicas de Productos
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{slug}', [ProductoController::class, 'show']);

// Rutas Públicas de Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas Privadas (Requieren Autenticación)
Route::middleware('auth:sanctum')->group(function () {
    // Usuario autenticado actual
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // Carrito de Compras
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);

    // Checkout

    // 1. Para pedirle el link de Stripe a Laravel
    Route::post('/checkout/iniciar', [CheckoutController::class, 'iniciarPago']);
    
    // 2. Para confirmar la orden en la BD una vez pagado
    Route::post('/checkout/confirmar', [CheckoutController::class, 'confirmarPago']);

});