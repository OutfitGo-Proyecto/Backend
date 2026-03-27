<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    /**
     * PASO 1: Crear pedido "Pendiente" y redirigir a Stripe
     */
    public function iniciarPago(Request $request)
    {
        // 1. Validar los datos del nuevo formulario (sin tarjeta)
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'notas' => 'nullable|string'
        ]);

        $user = $request->user();
        // Cargamos la variante y sus detalles
        $cartItems = CartItem::with(['variante.producto', 'variante.color', 'variante.talla'])->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío.'], 400);
        }

        $total = 0;
        
        // 2. Verificar stock de la variante antes de dejarle ir a pagar
        foreach ($cartItems as $item) {
            if ($item->variante->stock < $item->cantidad) {
                return response()->json([
                    'message' => "Stock insuficiente para: {$item->variante->producto->nombre} (Talla: {$item->variante->talla->nombre})"
                ], 422);
            }
            $total += $item->variante->producto->precio * $item->cantidad;
        }

        try {
            DB::beginTransaction();

            // 3. CREAR EL PEDIDO COMO PENDIENTE 
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'estado' => 'pendiente',
                'nombre' => $request->nombre,
                'apellidos' => $request->apellidos,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'ciudad' => $request->ciudad,
                'provincia' => $request->provincia,
                'codigo_postal' => $request->codigo_postal,
                'notas' => $request->notas,
            ]);

            // GUARDAMOS LA VARIANTE
            foreach ($cartItems as $item) {
                $order->orderItems()->create([
                    'producto_variante_id' => $item->producto_variante_id,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->variante->producto->precio,
                ]);
            }

            // 4. Configurar Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            $line_items = [];
            foreach ($cartItems as $item) {
                $line_items[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => ['name' => "{$item->variante->producto->nombre} - {$item->variante->color->nombre} / {$item->variante->talla->nombre}"],
                        'unit_amount' => $item->variante->producto->precio * 100,
                    ],
                    'quantity' => $item->cantidad,
                ];
            }

            // 5. Crear sesión de pago en Stripe
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                //Le pasamos el ID del pedido a Stripe en los metadatos de forma oculta
                'metadata' => [
                    'order_id' => $order->id 
                ],
                // Ajusta estas URLs a tu frontend. Le pasamos el session_id en la URL de éxito
                'success_url' => 'https://outfitgo.duckdns.org/pago-exitoso?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => 'https://outfitgo.duckdns.org/carrito',
            ]);

            DB::commit();

            // Devolvemos la URL a Angular para que mande al usuario allí
            return response()->json(['url' => $checkout_session->url], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al preparar el pago: ' . $e->getMessage()], 500);
        }
    }

    /**
     * PASO 2: Confirmar la compra 
     */
    public function confirmarPago(Request $request)
    {
        // Angular manda el session_id que le dio Stripe en la URL
        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            // 1. Verificación a Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($request->session_id);

            if ($session->payment_status !== 'paid') {
                return response()->json(['message' => 'El pago no se ha completado.'], 400);
            }

            // 2. Recuperamos el ID del pedido que escondimos en el Paso 1
            $orderId = $session->metadata->order_id;
            // Cargamos la variante para restarle el stock
            $order = Order::with('orderItems.variante')->findOrFail($orderId);

            if ($order->estado === 'pagado') {
                return response()->json(['message' => 'Este pedido ya estaba confirmado.', 'order' => $order], 200);
            }

            DB::beginTransaction();

            // 3. ACTUALIZAR A PAGADO
            $order->update(['estado' => 'pagado']);

            // 4. RESTAR EL STOCK REAL DE LA TIENDA
            foreach ($order->orderItems as $item) {
                $item->variante->decrement('stock', $item->cantidad);
            }

            // 5. VACIAR EL CARRITO DEL USUARIO
            CartItem::where('user_id', $order->user_id)->delete();

            DB::commit();

            return response()->json([
                'message' => '¡Pago verificado y compra completada con éxito!',
                'order' => $order
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al verificar el pago: ' . $e->getMessage()], 500);
        }
    }
}
