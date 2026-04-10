<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Listar todas las direcciones del usuario autenticado
     */
    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no detectado. Revisa el Token.'], 401);
        }

        $addresses = $user->addresses()->orderBy('es_principal', 'desc')->get();

        return response()->json($addresses);
    }

    /**
     * Guardar una nueva dirección
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_direccion' => 'required|string|max:50',
            'direccion'        => 'required|string|max:255',
            'ciudad'           => 'required|string|max:100',
            'provincia'        => 'required|string|max:100',
            'codigo_postal'    => 'required|string|max:10',
            'telefono'         => 'required|string|max:20',
            'es_principal'     => 'boolean'
        ]);

        return DB::transaction(function () use ($validated) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            // Si es la primera dirección que crea, la marcamos como principal sí o sí
            if ($user->addresses()->count() === 0) {
                $validated['es_principal'] = true;
            }

            // Si esta se marca como principal, desactivamos la anterior
            if (isset($validated['es_principal']) && $validated['es_principal']) {
                $user->addresses()->update(['es_principal' => false]);
            }

            $address = $user->addresses()->create($validated);

            return response()->json([
                'message' => 'Dirección guardada correctamente',
                'address' => $address
            ], 201);
        });
    }

    /**
     * Actualizar una dirección existente
     */
    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $address = $user->addresses()->findOrFail($id);

        $validated = $request->validate([
            'nombre_direccion' => 'sometimes|string|max:50',
            'direccion'        => 'sometimes|string|max:255',
            'ciudad'           => 'sometimes|string|max:100',
            'provincia'        => 'sometimes|string|max:100',
            'codigo_postal'    => 'sometimes|string|max:10',
            'telefono'         => 'sometimes|string|max:20',
            'es_principal'     => 'boolean'
        ]);

        return DB::transaction(function () use ($user, $address, $validated) {
            // Si el usuario intenta poner esta como principal, quitamos el check a las demás
            if (isset($validated['es_principal']) && $validated['es_principal']) {
                $user->addresses()->where('id', '!=', $address->id)->update(['es_principal' => false]);
            }

            $address->update($validated);

            return response()->json([
                'message' => 'Dirección actualizada',
                'address' => $address
            ]);
        });
    }

    /**
     * Eliminar una dirección
     */
    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $address = $user->addresses()->findOrFail($id);

        // Si borramos la principal y quedan más, hacemos principal la siguiente más reciente
        if ($address->es_principal) {
            $address->delete();
            $nextAddress = $user->addresses()->first();
            if ($nextAddress) {
                $nextAddress->update(['es_principal' => true]);
            }
        } else {
            $address->delete();
        }

        return response()->json(['message' => 'Dirección eliminada']);
    }

    /**
     * Método rápido para cambiar la dirección principal desde la UI
     */
    public function setPrimary($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $address = $user->addresses()->findOrFail($id);

        DB::transaction(function () use ($user, $address) {
            $user->addresses()->update(['es_principal' => false]);
            $address->update(['es_principal' => true]);
        });

        return response()->json(['message' => 'Dirección principal actualizada']);
    }
}
