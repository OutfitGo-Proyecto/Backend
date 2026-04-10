<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Crear Usuario</h1>
            <a href="{{ route('admin.usuarios.index') }}" class="text-gray-600 hover:text-gray-900 font-bold">Volver</a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.usuarios.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 font-bold mb-2">Nombre Completo</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Contraseña</label>
                <input type="password" name="password" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-gray-700 font-bold mb-2">Rol del Usuario</label>
                <select name="rol" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                    <option value="cliente" {{ old('rol') == 'cliente' ? 'selected' : '' }}>Cliente Normal</option>
                    <option value="admin_productos" {{ old('rol') == 'admin_productos' ? 'selected' : '' }}>Admin de Productos</option>
                    <option value="admin_usuarios" {{ old('rol') == 'admin_usuarios' ? 'selected' : '' }}>Admin de Usuarios</option>
                </select>
            </div>
            
            <hr class="my-6 border-gray-300">
            <h3 class="text-xl font-bold text-gray-800 mb-4"> Datos de Envío y Contacto</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">Dirección Completa</label>
                    <input type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Ej: Calle Mayor, 15, 2ºB" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Provincia</label>
                    <input type="text" list="lista-provincia" name="provincia" value="{{ old('provincia') }}" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                    <datalist id="lista-provincia">
                        <option value="Madrid">
                        <option value="Barcelona">
                        <option value="Valencia">
                        <option value="Sevilla">
                        <option value="Málaga">
                        <option value="Cádiz">
                    </datalist>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Ciudad</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad') }}" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Código Postal</label>
                    <input type="text" name="codigo_postal" value="{{ old('codigo_postal') }}" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Teléfono de Contacto</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                </div>
            </div>
            
            <div class="pt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold w-full">
                    Guardar Usuario
                </button>
            </div>
        </form>
    </div>
</body>
</html>
