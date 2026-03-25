<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Productos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Gestión de Productos</h1>        
            

            
            <a href="{{ route('productos.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold">
                + Nuevo Producto
            </a>            
            
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.logout') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-bold text-sm shadow">
                    Cerrar Sesión
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif


        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="p-3 border-b">ID</th>
                    <th class="p-3 border-b">Imagen</th>
                    <th class="p-3 border-b">Nombre</th>
                    <th class="p-3 border-b">Precio</th>
                    <th class="p-3 border-b">Stock</th>
                    <th class="p-3 border-b text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                    <tr class="hover:bg-gray-50">
                        
                        <td class="p-3 border-b">{{ $producto->id }}</td>
                        
                        <td class="p-3 border-b">
                            @if($producto->url_imagen_principal)
                                @if(str_starts_with($producto->url_imagen_principal, 'http'))
                                    <img src="{{ $producto->url_imagen_principal }}" alt="Foto Seeder" class="w-16 h-16 object-cover rounded shadow border border-gray-200">
                                @else
                                    <img src="{{ asset('storage/' . $producto->url_imagen_principal) }}" alt="Foto Local" class="w-16 h-16 object-cover rounded shadow border border-gray-200">
                                @endif
                            @else
                                <span class="text-gray-400 text-xs italic">Sin foto</span>
                            @endif
                        </td>   
                        
                        <td class="p-3 border-b font-semibold">{{ $producto->nombre }}</td>
                        
                        <td class="p-3 border-b">{{ $producto->precio }} €</td>
                        
                        <td class="p-3 border-b">
                            <span class="{{ $producto->stock < 5 ? 'text-red-500 font-bold' : 'text-gray-700' }}">
                                {{ $producto->stock }}
                            </span>
                        </td>
                        
                        <td class="p-3 border-b text-center space-x-2">
                            <a href="{{ route('productos.edit', $producto->id) }}" class="text-yellow-600 hover:text-yellow-800 font-bold">Editar</a>
                            
                            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que quieres borrar este producto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Borrar</button>
                            </form>
                        </td>
                    </tr>
                    @if($producto->estado === 'devolucion_solicitada')
                        <form action="{{ route('admin.pedidos.aprobar-devolucion', $producto->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Confirmar que el paquete ha llegado bien? Se devolverá el stock.');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-sm">
                                Aprobar Devolución
                            </button>
                        </form>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="p-3 border-b text-center text-gray-500">No hay productos en la base de datos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $productos->links() }}
        </div>

    </div>

</body>
</html>