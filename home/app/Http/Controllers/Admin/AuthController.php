<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        // 1. Recibimos los datos
        $email = $request->input('email');
        $password = $request->input('password');

        // 2. Comprobación manual
        if ($email === 'adminProductos@gmail.com' && $password === 'productos123') {
        
        session(['admin_identificado' => true]);
        session()->save();  
        
        return redirect('/admin/productos');
    }

        return back()->withErrors(['email' => 'Credenciales de administrador incorrectos.']);
    }

    // Para cerrar sesión
    public function logout(Request $request)
    {
        session()->forget('admin_identificado');
        return redirect('/admin/login');
    }   
}