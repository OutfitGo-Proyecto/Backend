<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario.
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'codigo_postal' => $request->codigo_postal,
            'provincia' => $request->provincia,
            'telefono' => $request->telefono,   
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Iniciar sesión.
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Si la cuenta del usuario esta suspendida no puede entrar.
        if ($user && !$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Tu cuenta ha sido suspendida. Contacta con el administrador.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Cerrar sesión.
     */
    public function logout(Request $request)
    {
        // Se eliminan todos los tokens del usuario actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Cierre de sesión exitoso',
        ]);
    }

    /**
     * Actualizar perfil del usuario autenticado.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Validamos los datos
        $validated = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'email'         => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password'      => 'nullable|string|min:8', // Opcional, por si quiere cambiarla
            'direccion'     => 'nullable|string|max:255',
            'ciudad'        => 'nullable|string|max:100',
            'provincia'     => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:10',
            'telefono'      => 'nullable|string|max:20',
        ]);

        // Si el usuario escribió una contraseña nueva, la encriptamos. Si la dejó vacía, la quitamos para no sobreescribirla con nada.
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Actualizamos la base de datos
        $user->update($validated);

        return response()->json([
            'message' => 'Perfil actualizado exitosamente',
            'user'    => $user
        ]);
    }
}
