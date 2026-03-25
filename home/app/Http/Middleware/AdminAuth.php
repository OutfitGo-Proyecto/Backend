<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si NO tienes la marca en la sesión, te echa al login
        if (!session('admin_identificado')) {
            return redirect('/admin/login');
        }
        
        // Si la tienes, te deja pasar
        return $next($request);
    }
}