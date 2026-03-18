<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order; 
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Devuelve el historial de pedidos del usuario logueado.
     */
    public function misPedidos(Request $request)
    {
        // 1.Buscamos los pedidos donde el user_id sea el del usuario actual
        $pedidos = Order::where('user_id', $request->user()->id)
                        ->latest()
                        ->get();

        // 2.Compruebo si tiene pedidos o si es un cliente nuevo
        if ($pedidos->isEmpty()) {
            return response()->json([
                'message' => 'Aún no tienes pedidos en tu historial.',
                'pedidos' => []
            ], 200);
        }

        // 4. Devuelvo la lista de pedidos a Angular
        return response()->json([
            'message' => 'Historial de pedidos recuperado con éxito.',
            'pedidos' => $pedidos
        ], 200);
    }
}