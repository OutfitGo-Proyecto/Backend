<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PedidoController extends Controller
{
    /**
     * Devuelve el historial de pedidos del usuario logueado.
     */
    public function misPedidos(Request $request)
    {
        // 1.Buscamos los pedidos donde el user_id sea el del usuario actual
        $pedidos = Order::where('user_id', $request->user()->id)
                        ->with('orderItems.product')
                        ->latest()  
                        ->paginate(5);


        // 2. Devuelvo la lista de pedidos a Angular
        return response()->json([
            'message' => 'Historial de pedidos recuperado con éxito.',
            'pedidos' => $pedidos
        ], 200);
    }

    public function cancelarPedido(Request $request, $id)
    {
        // 1. Buscamos el pedido por ID
        $pedido = Order::where('id', $id)
                        ->where('user_id', $request->user()->id) 
                        ->first();

        // 2. Si no existe o no es suyo, error 404
        if (!$pedido) {
            return response()->json([
                'message' => 'Pedido no encontrado.'
            ], 404);
        }

        // 3. Lógica de negocio: Solo se pueden cancelar pedidos con estado 'pendiente'
        if ($pedido->estado !== 'pendiente') {
            return response()->json([
                'message' => 'Este pedido ya ha sido enviado y no se puede cancelar.'
            ], 400);
        }

        // 4. Actualizamos el estado a 'cancelado'
        $pedido->estado = 'cancelado';
        $pedido->save();

        return response()->json([
            'message' => 'Pedido cancelado correctamente.',
            'pedido' => $pedido
        ], 200);
    }

    public function devolverPedido(Request $request, $id)
    {
        // 1. Buscamos el pedido por ID
        $pedido = Order::where('id', $id)
                        ->where('user_id', $request->user()->id) 
                        ->first();

        // 2. Si no existe o no es suyo, error 404
        if (!$pedido) {
            return response()->json([
                'message' => 'Pedido no encontrado.'
            ], 404);
        }

        // 3. Lógica de negocio: Solo se pueden devolver pedidos con estado 'enviado'
        if ($pedido->estado !== 'enviado') {
            return response()->json([
                'message' => 'Solo se pueden devolver pedidos que ya han sido enviados.'
            ], 400);
        }

        // 4. Actualizamos el estado a 'devolución solicitada'
        $pedido->estado = 'devolucion_solicitada';
        $pedido->save();

        return response()->json([
            'message' => 'Devolución solicitada correctamente.',
            'pedido' => $pedido
        ], 200);
    }
}