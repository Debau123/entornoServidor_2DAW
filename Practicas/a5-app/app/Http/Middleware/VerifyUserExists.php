<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyUserExists
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario autenticado todavía existe
        if (Auth::check() && !Auth::user()) {
            Auth::logout(); // Forzar el cierre de sesión
            return redirect('/login')->with('error', 'Tu usuario ya no existe en el sistema.');
        }

        // Continuar con la solicitud si todo está bien
        return $next($request);
    }
}
