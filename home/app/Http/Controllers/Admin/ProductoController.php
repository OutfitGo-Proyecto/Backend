<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Marca;      
use App\Models\Categoria;  
use App\Models\Talla;
use App\Models\Color;

class ProductoController extends Controller
{

    public function index()
    {
        // Traemos los productos ordenados por los más nuevos, de 10 en 10(mas control)
        $productos = Producto::orderBy('id', 'asc')->paginate(10);

        // Los productos que estan a punto de acabarse(yo lo veo mejor)
       // $productos = Producto::orderBy('stock', 'asc')->paginate(10);

       // Se los mando a la vista
        return view('index', compact('productos'));    
    }


    public function create()
    {
        // 1. Traemos las listas de la base de datos
        $marcas = Marca::all();
        $categorias = Categoria::all();
        $tallas = Talla::all();
        $colores = Color::all();

        // 2. Se las mandamos a la vista 
        return view('create', compact('marcas', 'categorias', 'tallas', 'colores'));
    }


    public function store(Request $request)
    {
        // 1. Validamos que nos mandan todo lo obligatorio
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'publico' => 'required|in:hombre,mujer,infantil,unisex',
            'marca_id' => 'required|integer',
            'tallas' => 'nullable|array',
            'colores' => 'nullable|array',
            'categoria_id' => 'required|integer',
            'imagen' => 'nullable|image|max:2048' 
        ]);

        // 2. Gestionamos la subida de la imagen
        $rutaImagen = null;
        if ($request->hasFile('imagen')) {
            // Guarda la foto en la carpeta public/productos y devuelve la ruta
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
        }

        // 3. Creamos el producto en la base de datos
        $producto = Producto::create([

            'nombre' => $request->nombre,
            'slug' => Str::slug($request->nombre) . '-' . rand(1000, 9999),
            'precio' => $request->precio,
            'stock' => $request->stock,
            'publico' => $request->publico,
            'marca_id' => $request->marca_id,
            'categoria_id' => $request->categoria_id,
            'url_imagen_principal' => $rutaImagen,    
        ]);

        // 4. Sincronizamos las Tallas y Colores seleccionados
        if ($request->has('tallas')) {
            $producto->tallas()->sync($request->tallas);
        }
        if ($request->has('colores')) {
            $producto->colores()->sync($request->colores);
        }
        // 5. Volvemos a la tabla de productos con un mensaje de éxito
            return redirect('/admin/productos')->with('success', '¡Producto creado con éxito!');    
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        // Buscamos el producto en la base de datos
        $producto = Producto::findOrFail($id);

        // 1. Traemos las listas de la base de datos
        $marcas = Marca::all();
        $categorias = Categoria::all();
        $tallas = Talla::all();
        $colores = Color::all();
        
        // Le mandamos el producto a la vista
        return view('edit', compact('producto', 'marcas', 'categorias', 'tallas', 'colores'));
    }

    public function update(Request $request, string $id)
    {
        // 1. Buscamos el producto
        $producto = Producto::findOrFail($id);

        // 2. Recogemos los datos del formulario 
        $datosActualizar = $request->except(['imagen', '_token', '_method', 'tallas', 'colores']);

        // 3. Si el usuario ha subido una imagen nueva lo guardamos
        if ($request->hasFile('imagen')) {

            $datosActualizar['url_imagen_principal'] = $request->file('imagen')->store('productos', 'public');
        }

        // 4. Actualizamos el producto con todos los datos
        $producto->update($datosActualizar);

        // 5. Sincronizamos las tallas y colores al editar
        $producto->tallas()->sync($request->input('tallas', []));
        $producto->colores()->sync($request->input('colores', []));

        // 6. Volvemos a la tabla
        return redirect('/admin/productos')->with('success', '¡Producto actualizado correctamente!');
    }


    public function destroy(string $id)
    {
        // 1. Buscamos el producto por su ID 
        $producto = Producto::findOrFail($id);

        // 2. Borramos la imagen física del disco duro
        if ($producto->url_imagen_principal) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($producto->url_imagen_principal);
        }

        // 3. Limpiamos las tablas intermedias (Tallas y Colores)
        $producto->tallas()->detach();
        $producto->colores()->detach();

        // 4. Lo eliminamos de la base de datos
        $producto->delete();

        // 5. Volvemos a la tabla con un mensaje verde
        return redirect('/admin/productos')->with('success', '¡Producto eliminado correctamente!');
    }
}
