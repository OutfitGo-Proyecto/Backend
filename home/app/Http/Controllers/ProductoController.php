<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Muestra el catálogo completo o los resultados de búsqueda.
     */
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta base
        $query = Producto::query();

        // 2. Lógica del Buscador: Si el usuario escribió algo en la barra (?q=Nike)
        if ($busqueda = $request->input('q')) {
            $query->where('nombre', 'LIKE', "%{$busqueda}%")
                  ->orWhere('descripcion', 'LIKE', "%{$busqueda}%")
                  // También buscamos por el nombre de la Marca relacionada
                  ->orWhereHas('marca', function($q) use ($busqueda) {
                      $q->where('nombre', 'LIKE', "%{$busqueda}%");
                  });
        }

        // 3. Cargar relaciones (Eager Loading) para optimizar
        // Traemos la 'marca' y las 'tiendas' para poder calcular el precio mínimo en la vista
        $productos = $query->with(['marca', 'tiendas'])
                           ->latest() // Los más nuevos primero
                           ->paginate(12); // Paginación de 12 en 12

        // 4. Retornamos la vista (que crearemos en el siguiente paso OUT-49)
        return view('productos.index', compact('productos'));
    }

    /**
     * Muestra la ficha de un producto específico para comparar precios.
     */
    public function show($slug)
    {
        // 1. Buscar el producto por su SLUG (URL amigable)
        $producto = Producto::where('slug', $slug)
            ->with(['marca', 'categoria', 'tiendas']) // Cargar todas las relaciones
            ->firstOrFail(); // Si no existe, lanza error 404

        // 2. Ordenar las tiendas por precio (de menor a mayor)
        // Esto es clave para el comparador: queremos ver la oferta más barata primero.
        $ofertas = $producto->tiendas->sortBy('pivot.precio');

        return view('productos.show', compact('producto', 'ofertas'));
    }
}