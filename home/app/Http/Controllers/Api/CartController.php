<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\Producto;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Ver el carrito del usuario autenticado.
     */
    public function index(Request $request)
    {
        $cartItems = CartItem::with('producto')
            ->where('user_id', $request->user()->id)
            ->get();

        return CartItemResource::collection($cartItems);
    }

    /**
     * Añadir un producto al carrito.
     */
    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => ['required', 'exists:productos,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        if ($producto->stock < $request->cantidad) {
            return response()->json([
                'message' => 'No hay suficiente stock disponible para este producto.',
            ], 422);
        }

        $cartItem = CartItem::where('user_id', $request->user()->id)
            ->where('producto_id', $request->producto_id)
            ->first();

        if ($cartItem) {
            $nuevaCantidad = $cartItem->cantidad + $request->cantidad;
            if ($producto->stock < $nuevaCantidad) {
                return response()->json([
                    'message' => 'No hay suficiente stock disponible para agregar esta cantidad.',
                ], 422);
            }
            $cartItem->update(['cantidad' => $nuevaCantidad]);
        } else {
            $cartItem = CartItem::create([
                'user_id' => $request->user()->id,
                'producto_id' => $request->producto_id,
                'cantidad' => $request->cantidad,
            ]);
        }

        // Cargar la relación producto antes de devolver el recurso
        $cartItem->load('producto');

        return response()->json([
            'message' => 'Producto añadido al carrito exitosamente',
            'item' => new CartItemResource($cartItem),
        ], 201);
    }

    /**
     * Eliminar un item del carrito.
     */
    public function destroy(Request $request, $id)
    {
        $cartItem = CartItem::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'message' => 'Producto eliminado del carrito exitosamente',
        ]);
    }
}
