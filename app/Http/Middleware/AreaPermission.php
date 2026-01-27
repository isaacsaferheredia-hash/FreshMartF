<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AreaPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $area
     * @param  string  $accion
     */
    public function handle(Request $request, Closure $next, string $area, string $accion): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        if (!$user->puede($area, $accion)) {
            abort(403, 'No tiene permisos para realizar esta acción.');
        }

        return $next($request);
    }
}
