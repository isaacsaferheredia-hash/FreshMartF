<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, ...$parametros)
    {
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        /**
         * 🔓 ADMIN y JEFE pasan siempre
         */
        if (in_array($user->rol, ['ADMIN', 'JEFE'])) {
            return $next($request);
        }

        /**
         * 🔎 Buscar si el usuario tiene AL MENOS UN ÁREA válida
         * dentro de los parámetros enviados
         *
         * Ej: rol:INVENTARIO,ADMIN,JEFE
         * → aquí solo usamos INVENTARIO
         */
        $areasPermitidas = array_filter($parametros, function ($p) {
            return ! in_array($p, ['ADMIN', 'JEFE', 'AUXILIAR', 'OPERATIVO']);
        });

        if (empty($areasPermitidas)) {
            abort(403);
        }

        $tieneArea = DB::table('usuario_area_rol')
            ->where('user_id', $user->id)
            ->whereIn('id_area', $areasPermitidas)
            ->exists();

        if (! $tieneArea) {
            abort(403, 'No tiene permisos para este módulo');
        }

        return $next($request);
    }
}
