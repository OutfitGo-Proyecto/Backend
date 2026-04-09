<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirigir al usuario a la página de autenticación de Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Obtener la información del usuario de Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Buscar si ya existe un usuario con este google_id o email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if (!$user) {
                // Crear un nuevo usuario si no existe
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => null, // O Hash::make(Str::random(24))
                ]);
            } else {
                // Actualizar google_id y avatar si ya existía por email
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            }

            // Generar token de Sanctum
            $token = $user->createToken('social_token')->plainTextToken;

            // Redirigir al frontend con el token en la URL
            // Supongamos que el frontend está en https://outfitgo.duckdns.org
            // Y queremos landing en la página de login para que el componente capture el token
            $frontendUrl = env('FRONTEND_URL', 'https://outfitgo.duckdns.org/login');
            
            return redirect()->to($frontendUrl . '?token=' . $token);

        } catch (\Exception $e) {
            return redirect()->to(env('FRONTEND_URL', 'https://outfitgo.duckdns.org/login') . '?error=social_auth_failed');
        }
    }
}
