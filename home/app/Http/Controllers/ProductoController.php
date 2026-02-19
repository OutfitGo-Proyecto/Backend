<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Muestra el catálogo completo o los resultados de búsqueda con filtros.
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        // 1. Buscador Global
        if ($busqueda = $request->input('q')) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'LIKE', "%{$busqueda}%")
                  ->orWhere('descripcion', 'LIKE', "%{$busqueda}%")
                  ->orWhereHas('marca', function($qMarca) use ($busqueda) {
                      $qMarca->where('nombre', 'LIKE', "%{$busqueda}%");
                  });
            });
        }

        // 2. Filtros
        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtro por Tallas (acepta múltiples separadas por coma: ?talla=S,M)
        if ($request->filled('talla')) {
            $tallas = explode(',', $request->talla);
            $query->whereHas('tallas', function($q) use ($tallas) {
                $q->whereIn('nombre', $tallas);
            });
        }

        // Filtro por Colores (acepta múltiples: ?color=Rojo,Azul)
        if ($request->filled('color')) {
            $colores = explode(',', $request->color);
            $query->whereHas('colores', function($q) use ($colores) {
                $q->whereIn('nombre', $colores);
            });
        }

        // Filtro por Rango de Precio (busca si ALGUNA tienda tiene el precio en rango)
        if ($request->filled('precio_min')) {
            $query->whereHas('tiendas', function($q) use ($request) {
                $q->where('precio', '>=', $request->precio_min);
            });
        }

        if ($request->filled('precio_max')) {
            $query->whereHas('tiendas', function($q) use ($request) {
                $q->where('precio', '<=', $request->precio_max);
            });
        }

        // 3. Cargar relaciones y paginar
        $productos = $query->with(['marca', 'tiendas', 'tallas', 'colores'])
                           ->latest()
                           ->paginate(12);

        return response()->json($productos);
    }

    /**
     * Muestra la ficha de un producto específico.
     */
    public function show($slug)
    {
        $producto = Producto::where('slug', $slug)
            ->with(['marca', 'categoria', 'tiendas', 'tallas', 'colores'])
            ->firstOrFail();

        return response()->json($producto);
    }
}