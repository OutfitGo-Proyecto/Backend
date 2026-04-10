<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar Usuario: {{ $usuario->name }}</h1>
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

        <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT') <div>
                <label class="block text-gray-700 font-bold mb-2">Nombre Completo</label>
                <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Nueva Contraseña <span class="text-sm font-normal text-gray-500">(Opcional)</span></label>
                <input type="password" name="password" placeholder="Déjalo en blanco para no cambiarla" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Rol del Usuario</label>
                <select name="rol" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                    <option value="cliente" {{ (isset($usuario) && $usuario->rol == 'cliente') ? 'selected' : '' }}>Cliente Normal</option>
                    <option value="admin_productos" {{ (isset($usuario) && $usuario->rol == 'admin_productos') ? 'selected' : '' }}>Admin de Productos</option>
                    <option value="admin_usuarios" {{ (isset($usuario) && $usuario->rol == 'admin_usuarios') ? 'selected' : '' }}>Admin de Usuarios</option>
                </select>
            </div>
            <hr class="my-6 border-gray-300">
            <h3 class="text-xl font-bold text-gray-800 mb-4"> Datos de Envío y Contacto</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">Dirección Completa</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $usuario->direccion ?? '') }}" placeholder="Ej: Calle Mayor, 15, 2ºB" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Ciudad</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', $usuario->ciudad ?? '') }}" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Provincia</label>
                    <input type="text" list="lista-provincia" name="provincia" value="{{ old('provincia', $usuario->provincia ?? '') }}" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                    <datalist id="lista-provincia">
                        <option value="Álava">Álava</option>
                        <option value="Albacete">Albacete</option>
                        <option value="Alicante">Alicante</option>
                        <option value="Almería">Almería</option>
                        <option value="Asturias">Asturias</option>
                        <option value="Ávila">Ávila</option>
                        <option value="Badajoz">Badajoz</option>
                        <option value="Barcelona">Barcelona</option>
                        <option value="Burgos">Burgos</option>
                        <option value="Cáceres">Cáceres</option>
                        <option value="Cádiz">Cádiz</option>
                        <option value="Cantabria">Cantabria</option>
                        <option value="Castellón">Castellón</option>
                        <option value="Ceuta">Ceuta</option>
                        <option value="Ciudad Real">Ciudad Real</option>
                        <option value="Córdoba">Córdoba</option>
                        <option value="Cuenca">Cuenca</option>
                        <option value="Girona">Girona</option>
                        <option value="Granada">Granada</option>
                        <option value="Guadalajara">Guadalajara</option>
                        <option value="Guipúzcoa">Guipúzcoa</option>
                        <option value="Huelva">Huelva</option>
                        <option value="Huesca">Huesca</option>
                        <option value="Illes Balears">Illes Balears</option>
                        <option value="Jaén">Jaén</option>
                        <option value="La Coruña">La Coruña</option>
                        <option value="La Rioja">La Rioja</option>
                        <option value="Las Palmas">Las Palmas</option>
                        <option value="León">León</option>
                        <option value="Lleida">Lleida</option>
                        <option value="Lugo">Lugo</option>
                        <option value="Madrid">Madrid</option>
                        <option value="Málaga">Málaga</option>
                        <option value="Melilla">Melilla</option>
                        <option value="Murcia">Murcia</option>
                        <option value="Navarra">Navarra</option>
                        <option value="Ourense">Ourense</option>
                        <option value="Palencia">Palencia</option>
                        <option value="Pontevedra">Pontevedra</option>
                        <option value="Salamanca">Salamanca</option>
                        <option value="Segovia">Segovia</option>
                        <option value="Sevilla">Sevilla</option>
                        <option value="Soria">Soria</option>
                        <option value="Tarragona">Tarragona</option>
                        <option value="Santa Cruz de Tenerife">Santa Cruz de Tenerife</option>
                        <option value="Teruel">Teruel</option>
                        <option value="Toledo">Toledo</option>
                        <option value="Valencia">Valencia</option>
                        <option value="Valladolid">Valladolid</option>
                        <option value="Vizcaya">Vizcaya</option>
                        <option value="Zamora">Zamora</option>
                        <option value="Zaragoza">Zaragoza</option>
                    </datalist>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Código Postal</label>
                    <input type="text" name="codigo_postal" value="{{ old('codigo_postal', $usuario->codigo_postal ?? '') }}" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2">Teléfono de Contacto</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono ?? '') }}" class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                </div>

            </div>
            <div class="pt-4">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 font-bold w-full">
                    Actualizar Usuario
                </button>
            </div>
        </form>

    </div>

</body>
</html>