<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Mostrar formulario de login admin
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Procesar login admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Credenciales incorrectas.',
            ]);
        }

        // ✅ SOLO autenticamos
        $request->session()->regenerate();

        // ❗❗ NO validar roles aquí ❗❗
        // ❗❗ NO bloquear por users.rol ❗❗

        return redirect()->route('homeback');
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
