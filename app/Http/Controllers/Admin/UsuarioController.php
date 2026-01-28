<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->withTrashed();

        // 🔍 Filtro por estado
        if ($request->estado === 'ACTIVO') {
            $query->whereNull('deleted_at');
        }

        if ($request->estado === 'INACTIVO') {
            $query->onlyTrashed();
        }

        // 🔍 Filtro por rol
        if ($request->rol) {
            $query->where('rol', $request->rol);
        }

        $usuarios = $query->orderBy('name')->paginate(10);

        return view('admin.Usuarios.index', compact('usuarios'));
    }


    public function create()
    {
        return view('admin.Usuarios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'rol'      => 'required|in:ADMIN,JEFE,AUXILIAR,OPERATIVO',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'rol'      => $data['rol'],
        ]);

        return redirect()->route('Usuarios.Index')
            ->with('success', 'Usuario creado correctamente');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puede desactivar su propio usuario');
        }

        $user->delete(); // 👈 soft delete

        return redirect()
            ->route('Usuarios.Index')
            ->with('success', 'Usuario desactivado correctamente');
    }
}
