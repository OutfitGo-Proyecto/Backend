<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Middleware\AdminAuth;  

Route::get('/', [ProductoController::class, 'index'])->name('home');

// Ruta alternativa si quieres una url tipo /catalogo
Route::get('/catalogo', [ProductoController::class, 'index'])->name('productos.index');

// Ruta para ver el detalle de un producto (Comparador)
Route::get('/producto/{slug}', [ProductoController::class, 'show'])->name('productos.show');

// Rutas públicas (El formulario de login)
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Rutas para el Panel de Administrador
Route::prefix('admin')->middleware(AdminAuth::class)->group(function () {
    Route::resource('productos', AdminProductoController::class);
    Route::put('/pedidos/{id}/aprobar-devolucion', [AdminProductoController::class, 'aprobarDevolucion'])
    ->name('admin.pedidos.aprobar-devolucion');
});