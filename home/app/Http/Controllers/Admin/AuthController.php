<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Muestra el formulario de login
    public function showLoginForm()
    {
        return view('login');
    }

    // Comprueba el usuario y contraseña
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $rol = Auth::user()->rol;
            
            // Redirigir según rol
            if (in_array($rol, ['admin_usuarios', 'admin'])) {
                return redirect()->route('admin.usuarios.index');
            } else if ($rol === 'admin_productos') {
                return redirect()->route('admin.productos.index'); 
            }
            
            // Si es un cliente normal
            Auth::logout();
            return back()->withErrors(['email' => 'Tu usuario no tiene rol de administrador.']);
        }

        return back()->withErrors(['email' => 'Credenciales de administrador incorrectos.']);
    }

    // Para cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }   
}