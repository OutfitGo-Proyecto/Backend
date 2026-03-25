<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Producto;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Listar los favoritos del usuario autenticado
     */
    public function index(Request $request)
    {
        // Obtenemos los favoritos cargando la relación con el producto y sus imágenes
        $favorites = $request->user()->favorites()
            ->with(['producto.marca', 'producto.categoria', 'producto.imagenes', 'producto.colores', 'producto.tallas'])
            ->latest()
            ->get();

        return response()->json($favorites);
    }

    /**
     * Guardar un nuevo favorito
     */
    public function store(Request $request)
    {
        // Primero valido los datos, para asegurarme de que el producto existe
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
        ]);

        // Saco el ID del usuario autenticado
        $userId = $request->user()->id;

        // Compruebo si ya está en favoritos para no duplicarlo
        $existe = Favorite::where('user_id', $userId)
            ->where('producto_id', $request->producto_id)
            ->first();

        if ($existe) {
            return response()->json(['message' => 'Este producto ya está en tu lista de favoritos'], 422);
        }

        // Creamos el registro en la base de datos
        Favorite::create([
            'user_id' => $userId,
            'producto_id' => $request->producto_id,
        ]);

        return response()->json(['message' => 'Se ha guardado en favoritos correctamente'], 201);
    }

    /**
     * Eliminar un favorito
     */
    public function destroy(Request $request, $id)
    {
        // Buscamos el favorito que pertenezca al usuario (seguridad) para borrarlo por producto_id
        $favorite = $request->user()->favorites()->where('producto_id', $id)->first();

        if (!$favorite) {
            return response()->json(['message' => 'No se encontró el favorito o no tienes permiso'], 404);
        }

        $favorite->delete();

        return response()->json(['message' => 'Eliminado de favoritos correctamente']);
    }
}
