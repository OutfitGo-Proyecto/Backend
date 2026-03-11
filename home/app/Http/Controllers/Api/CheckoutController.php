<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Procesar el checkout: calcular total, crear orden, restar stock y vaciar carrito.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        $cartItems = CartItem::with('producto')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío.'], 400);
        }

        $total = 0;
        
        // Verificar stock para todos los productos antes de procesar
        foreach ($cartItems as $item) {
            if ($item->producto->stock < $item->cantidad) {
                return response()->json([
                    'message' => "Stock insuficiente para el producto: {$item->producto->nombre}"
                ], 422);
            }
            $total += $item->producto->precio * $item->cantidad;
        }

        try {
            DB::beginTransaction();

            // Crear la orden
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'estado' => 'completado',
            ]);

            // Añadir items a la orden y restar stock
            foreach ($cartItems as $item) {
                $order->orderItems()->create([
                    'producto_id' => $item->producto_id,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->producto->precio,
                ]);

                // Restar stock
                $item->producto->decrement('stock', $item->cantidad);
            }

            // Vaciar el carrito
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            // Cargar los items para la respuesta
            $order->load('orderItems.producto');

            return response()->json([
                'message' => 'Compra realizada con éxito',
                'order' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al procesar el checkout: ' . $e->getMessage()
            ], 422);
        }
    }
}
