<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistorialPrecio;
use Illuminate\Http\Request;

class ControladorHistorialPrecio extends Controller
{
    /**
     * Obtener el historial de precios de un producto.
     *
     * @param  int  $productoId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($productoId)
    {
        // Obtener historial con la tienda asociada
        $historial = HistorialPrecio::with('tienda')
            ->where('producto_id', $productoId)
            ->orderBy('fecha_registro', 'desc')
            ->get();

        // Formatear la respuesta
        $datos = $historial->map(function ($registro) {
            return [
                'nombre_tienda' => $registro->tienda ? $registro->tienda->nombre : 'Tienda Desconocida',
                'precio' => (float) $registro->precio,
                'fecha' => $registro->fecha_registro->toDateTimeString(),
            ];
        });

        return response()->json($datos);
    }
}
