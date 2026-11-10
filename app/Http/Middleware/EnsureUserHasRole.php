<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! in_array($request->user()?->rol, $roles)) {

            // En la API devolvemos JSON en vez de una pantalla de error HTML.
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'No tienes permiso para realizar esta acción.',
                ], 403);
            }

            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
