<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class SetLanguage
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Prioridad: 1. Header X-Lang, 2. Accept-Language, 3. Config default
        $locale = $request->header('X-Lang') ?: $request->getPreferredLanguage(['es', 'en', 'fr']);
        
        if (in_array($locale, ['es', 'en', 'fr'])) {
            App::setLocale($locale);
        } else {
            App::setLocale('es');
        }

        return $next($request);
    }
}
