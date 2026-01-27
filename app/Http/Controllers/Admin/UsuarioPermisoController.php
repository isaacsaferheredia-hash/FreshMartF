<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioPermisoController extends Controller
{
    public function edit(User $user)
    {
        $areas = DB::table('areas')->get();
        $roles = DB::table('roles')->get();

        $permisosActuales = DB::table('usuario_area_rol')
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('id_area');

        return view('admin.Usuarios.permisos', compact(
            'user',
            'areas',
            'roles',
            'permisosActuales'
        ));
    }

    public function update(Request $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {

            // 1️⃣ Borrar permisos anteriores
            DB::table('usuario_area_rol')
                ->where('user_id', $user->id)
                ->delete();

            // 2️⃣ Insertar SOLO permisos válidos
            if ($request->has('permisos')) {
                foreach ($request->permisos as $area => $rol) {

                    // ⛔ Si no tiene rol, no se inserta
                    if (empty($rol)) {
                        continue;
                    }

                    DB::table('usuario_area_rol')->insert([
                        'user_id' => $user->id,
                        'id_area' => $area,
                        'id_rol'  => $rol,
                    ]);
                }
            }
        });

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Permisos actualizados correctamente');
    }
}
