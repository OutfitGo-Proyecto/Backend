<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Gestión de Usuarios</h1>
            
            <a href="{{ route('admin.usuarios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-bold shadow">
                + Nuevo Usuario
            </a>
            
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.logout') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-bold text-sm shadow">
                    Cerrar Sesión
                </a>
            </div>
        </div>
        <style>
            @keyframes desaparecer {
                0%   { opacity: 1; max-height: 200px; }
                70%  { opacity: 1; max-height: 200px; } 
                90%  { opacity: 0; max-height: 200px; padding: 0.75rem 1rem; margin-bottom: 1rem; border-width: 1px; } 
                100% { opacity: 0; max-height: 0; padding: 0; margin-bottom: 0; border-width: 0; overflow: hidden; } 
            }
            .alerta-temporal {
                animation: desaparecer 4s forwards; 
                overflow: hidden; 
            }
        </style>


            @if(session('success'))
                <div class="alerta-temporal bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
                    <span class="block sm:inline font-bold">¡Genial!</span>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alerta-temporal bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif


        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                <h3 class="text-gray-500 text-sm font-bold uppercase">Total Usuarios</h3>
                <p class="text-3xl font-bold text-gray-800">{{ $totalUsuarios }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                <h3 class="text-gray-500 text-sm font-bold uppercase">Activos</h3>
                <p class="text-3xl font-bold text-gray-800">{{ $activos }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
                <h3 class="text-gray-500 text-sm font-bold uppercase">Suspendidos</h3>
                <p class="text-3xl font-bold text-gray-800">{{ $suspendidos }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            
            <form action="{{ route('admin.usuarios.index') }}" method="GET" class="mb-6 flex gap-4">
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre o email..." class="flex-1 border border-gray-300 px-4 py-2 rounded focus:outline-none focus:border-blue-500">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded font-bold hover:bg-gray-900">Buscar</button>
                @if($buscar)
                    <a href="{{ route('admin.usuarios.index') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded font-bold hover:bg-gray-400 text-center content-center">Limpiar</a>
                @endif
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="p-3 border-b">ID</th>
                            <th class="p-3 border-b">Nombre</th>
                            <th class="p-3 border-b">Email</th>
                            <th class="p-3 border-b">Rol</th>
                            <th class="p-3 border-b">Estado</th>
                            <th class="p-3 border-b">Registro</th>
                            <th class="p-3 border-b text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b font-bold">{{ $user->id }}</td>
                                <td class="p-3 border-b">{{ $user->name }}</td>
                                <td class="p-3 border-b">{{ $user->email }}</td>
                                <td class="p-3 border-b uppercase text-xs font-bold text-gray-600">{{ str_replace('_', ' ', $user->rol) }}</td>
                                <td class="p-3 border-b">
                                    @if($user->is_active)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Activo</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Suspendido</span>
                                    @endif
                                </td>
                                <td class="p-3 border-b">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="p-3 border-b text-center space-x-2">
                                    <a href="{{ route('admin.usuarios.show', $user->id) }}" class="text-blue-500 hover:text-blue-700 font-bold" title="Ver ficha">Ficha</a>
                                    <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="text-yellow-500 hover:text-yellow-700 font-bold" title="Editar">Editar</a>
                                    
                                    <form action="{{ route('admin.usuarios.toggleStatus', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $user->is_active ? 'text-orange-500 hover:text-orange-700' : 'text-green-500 hover:text-green-700' }} font-bold" title="{{ $user->is_active ? 'Suspender' : 'Reactivar' }}">
                                            {{ $user->is_active ? '⏸️' : '▶️' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.usuarios.forceDelete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que quieres eliminar este usuario permanentemente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold" title="Borrar">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $usuarios->links() }}
            </div>
            
        </div>
    </div>
</body>
</html>
