<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Mail\ConfirmacionCompra;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function iniciarPago(Request $request)
    {
        $request->validate([
            'address_id' => 'required|integer'
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        
        $address = $user->addresses()->findOrFail($request->address_id);

        $cartItems = CartItem::with(['variante.producto', 'variante.color', 'variante.talla'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío.'], 400);
        }

        $total = 0;
        
        foreach ($cartItems as $item) {
            $variante = $item->variante;
            if (!$variante) {
                return response()->json(['message' => 'Error: El producto ya no tiene variantes disponibles.'], 422);
            }

            if ($variante->stock < $item->cantidad) {
                return response()->json([
                    'message' => "Stock insuficiente para: {$variante->producto->nombre} (Talla: {$variante->talla->nombre})"
                ], 422);
            }
            $total += $variante->producto->precio * $item->cantidad;
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id'       => $user->id,
                'total'         => $total,
                'estado'        => 'pendiente',
                'nombre'        => $user->name,
                'apellidos'     => '', 
                'telefono'      => $address->telefono,
                'direccion'     => $address->direccion,
                'ciudad'        => $address->ciudad,
                'provincia'     => $address->provincia,
                'codigo_postal' => $address->codigo_postal,
                'notas'         => $request->notas ?? '',
            ]);

            foreach ($cartItems as $item) {
                $order->orderItems()->create([
                    'producto_id'          => $item->variante->producto_id,
                    'producto_variante_id' => $item->producto_variante_id,
                    'cantidad'             => $item->cantidad,
                    'precio_unitario'      => $item->variante->producto->precio,
                ]);
            }

            Stripe::setApiKey(config('services.stripe.secret'));

            $line_items = [];
            foreach ($cartItems as $item) {
                $unit_amount = (int) round($item->variante->producto->precio * 100);

                $line_items[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => "{$item->variante->producto->nombre} - {$item->variante->color->nombre} / {$item->variante->talla->nombre}"
                        ],
                        'unit_amount' => $unit_amount,
                    ],
                    'quantity' => $item->cantidad,
                ];
            }

            $frontend_url = env('FRONTEND_URL', 'https://outfitgo.duckdns.org');

            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items'           => $line_items,
                'mode'                 => 'payment',
                'metadata'             => [
                    'order_id' => $order->id 
                ],
                'success_url'          => rtrim($frontend_url, '/') . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'           => rtrim($frontend_url, '/') . '/cart',
            ]);

            DB::commit();

            return response()->json(['url' => $checkout_session->url], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al preparar el pago: ' . $e->getMessage()], 500);
        }
    }

    public function confirmarPago(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($request->session_id);

            if ($session->payment_status !== 'paid') {
                return response()->json(['message' => 'El pago no se ha completado.'], 400);
            }

            $orderId = $session->metadata->order_id ?? null;
            if (!$orderId) {
                return response()->json(['message' => 'La sesión de Stripe no contiene un pedido válido.'], 422);
            }

            $order = Order::with(['user', 'orderItems.variante'])->findOrFail($orderId);

            if ((int) $order->user_id !== (int) $request->user()->id) {
                return response()->json(['message' => 'No autorizado para confirmar este pedido.'], 403);
            }

            if ($order->estado === 'pagado') {
                return response()->json(['message' => 'Este pedido ya estaba confirmado.', 'order' => $order], 200);
            }

            DB::beginTransaction();

            foreach ($order->orderItems as $item) {
                if (!$item->variante) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'No se puede confirmar el pedido: una variante ya no existe.'
                    ], 422);
                }

                if ($item->variante->stock < $item->cantidad) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'No se puede confirmar el pedido: stock insuficiente en una variante.'
                    ], 422);
                }
            }

            $order->update(['estado' => 'pagado']);

            foreach ($order->orderItems as $item) {
                $item->variante->decrement('stock', $item->cantidad);
            }

            CartItem::where('user_id', $order->user_id)->delete();

            DB::commit();

            Mail::to($order->user->email)->send(new ConfirmacionCompra($order));

            return response()->json([
                'message' => '¡Pago verificado y compra completada con éxito!',
                'order' => $order
            ], 200);

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            return response()->json(['message' => 'Error al verificar el pago: ' . $e->getMessage()], 500);
        }
    }
}