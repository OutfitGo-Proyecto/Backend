<?php

use App\Http\Controllers\Api\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\FavoriteController;



// Rutas Públicas de Productos
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{slug}', [ProductoController::class, 'show']);

// Rutas Públicas de Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Autenticación Social (Google)
Route::get('/auth/google/redirect', [\App\Http\Controllers\Api\SocialAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [\App\Http\Controllers\Api\SocialAuthController::class, 'handleGoogleCallback']);



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
    Route::patch('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);

    // Checkout

    // 1. Para pedirle el link de Stripe a Laravel
    Route::post('/checkout/iniciar', [CheckoutController::class, 'iniciarPago']);

    // 2. Para confirmar la orden en la BD una vez pagado
    Route::post('/checkout/confirmar', [CheckoutController::class, 'confirmarPago']);



    // Historial de pedidos
    Route::get('/pedidos', [PedidoController::class, 'misPedidos']);

    // Cancelar pedido
    Route::post('/pedidos/{id}/cancelar', [PedidoController::class, 'cancelarPedido']);

    // Devolver pedido
    Route::post('/pedidos/{id}/devolver', [PedidoController::class, 'devolverPedido']);

    // Favoritos
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);

    // Editar datos usuario
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Direcciones
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
    Route::patch('/addresses/{id}/set-primary', [AddressController::class, 'setPrimary']);

    // Cupones
    Route::post('/cupon/validar', [\App\Http\Controllers\Api\CheckoutController::class, 'validarCupon']);
});
