<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Editar: {{ $producto->nombre }}</h1>
            <a href="/admin/productos" class="text-gray-500 hover:underline">Cancelar y volver</a>
        </div>

        <form action="{{ route('productos.update', $producto->id) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            
            @csrf
            @method('PUT')

            <div>
                <label class="block text-gray-700 font-bold mb-2">Nombre de la prenda</label>
                <input type="text" name="nombre" value="{{ $producto->nombre }}" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Precio (€)</label>
                    <input type="number" step="0.01" name="precio" value="{{ $producto->precio }}" class="w-full border p-2 rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Stock Disponible</label>
                    <input type="number" name="stock" value="{{ $producto->stock }}" class="w-full border p-2 rounded" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Marca</label>
                    <select name="marca_id" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecciona una marca...</option>
                        @foreach($marcas as $marca)
                            <option value="{{ $marca->id }}" {{ $producto->marca_id == $marca->id ? 'selected' : '' }}>
                                {{ $marca->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Categoría</label>
                    <select name="categoria_id" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecciona una categoría...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ $producto->categoria_id == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>  
            </div>
            <div class="grid grid-cols-2 gap-6 p-4 border rounded bg-gray-50">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Tallas Disponibles</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($tallas as $talla)
                            <label class="flex items-center space-x-2 text-sm">
                                <input type="checkbox" name="tallas[]" value="{{ $talla->id }}" 
                                    class="rounded text-blue-500" 
                                    {{ in_array($talla->id, $producto->tallas->pluck('id')->toArray()) ? 'checked' : '' }}>
                                <span>{{ $talla->nombre }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Colores</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($colores as $color)
                            <label class="flex items-center space-x-2 text-sm">
                                <input type="checkbox" name="colores[]" value="{{ $color->id }}" 
                                    class="rounded text-blue-500" 
                                    {{ in_array($color->id, $producto->colores->pluck('id')->toArray()) ? 'checked' : '' }}>
                                <span class="w-3 h-3 inline-block rounded-full border border-gray-300" style="background-color: {{ $color->hex_code }}"></span>
                                <span>{{ $color->nombre }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>  

            <div>
                <label class="block text-gray-700 font-bold mb-2">Público</label>
                <select name="publico" class="w-full border p-2 rounded">
                    <option value="unisex" {{ $producto->publico == 'unisex' ? 'selected' : '' }}>Unisex</option>
                    <option value="hombre" {{ $producto->publico == 'hombre' ? 'selected' : '' }}>Hombre</option>
                    <option value="mujer" {{ $producto->publico == 'mujer' ? 'selected' : '' }}>Mujer</option>
                    <option value="infantil" {{ $producto->publico == 'infantil' ? 'selected' : '' }}>Infantil</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Foto Principal</label>
                
                @if($producto->url_imagen_principal)
                    <div class="mb-3">
                        <p class="text-sm text-gray-500 mb-1">Foto actual:</p>
                        <img src="{{ asset('storage/' . $producto->url_imagen_principal) }}" alt="Foto" class="w-32 h-32 object-cover rounded shadow border border-gray-200">
                    </div>
                @endif
                
                <input type="file" name="imagen" accept="image/*" class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Sube una nueva imagen solo si quieres reemplazar la actual.</p>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 transition">
                    Guardar Cambios
                </button>
            </div>
        </form>

    </div>

</body>
</html>