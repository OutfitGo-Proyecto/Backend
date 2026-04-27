<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\ResenaProducto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
 
class ResenaProductoController extends Controller
{
    public function store(Request $request, $productId): JsonResponse
    {
        $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);
 
        $resena = new ResenaProducto();
        $resena->puntuacion = $request->puntuacion;
        $resena->comentario = $request->comentario;
        $resena->producto_id = $productId;
        $resena->user_id = $request->user()->id;
 
        $resena->save();
 
        return response()->json([
            'mensaje' => 'Reseña publicada con éxito.',
            'resena' => $resena->load('user:id,name')
        ], 201);
    }
}
