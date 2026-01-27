<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Ciudad;


class RegisteredUserController extends Controller
{
    private function cleanRequest(Request $request): void
    {
        $request->merge(
            collect($request->all())->map(function ($value) {
                return is_string($value) ? trim($value) : $value;
            })->toArray()
        );
    }

    public function create(): View
    {
        return view('auth.register');
    }


    public function showRegisterFull(): View
    {
        $ciudades = Ciudad::orderBy('ciu_descripcion')->get();

        return view('auth.register-full', compact('ciudades'));
    }

    public function checkCedula(Request $request): RedirectResponse
    {
        $this->cleanRequest($request);

        $request->validate([
            'cli_ruc_ced' => [
                'required',
                'regex:/^\d{10}$|^\d{13}$/'
            ],
        ]);


        $cliente = Cliente::where('cli_ruc_ced', $request->cli_ruc_ced)->first();

        if (!$cliente) {
            session([
                'cli_ruc_ced' => $request->cli_ruc_ced,
            ]);

            return redirect()->route('register.full');
        }

        if (User::where('cli_id', $cliente->id_cliente)->exists()) {
            return back()->withErrors([
                'cli_ruc_ced' => 'Este cliente ya tiene un usuario registrado.',
            ]);
        }

        // 🔥 SESIÓN PERSISTENTE
        session([
            'cliente_id'  => $cliente->id_cliente,
            'correo_real' => trim($cliente->cli_mail),
        ]);

        return redirect()->route('register.confirm');
    }

    public function confirmEmail(Request $request)
    {
        $this->cleanRequest($request);

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Recuperar cliente desde sesión persistente
        $clienteId = session('cliente_id');

        if (!$clienteId) {
            return redirect()
                ->route('register')
                ->withErrors([
                    'email' => 'La sesión expiró. Intenta nuevamente.',
                ]);
        }

        $cliente = Cliente::findOrFail($clienteId);

        // Normalizar correos (trim + lowercase)
        $correoCliente   = strtolower(trim($cliente->cli_mail));
        $correoIngresado = strtolower(trim($request->email));

        if ($correoCliente !== $correoIngresado) {
            return back()->withErrors([
                'email' => 'El correo no coincide. Intenta nuevamente.',
            ]);
        }

        session(['cliente_id' => $cliente->id_cliente]);

        return redirect()->route('register.password');

    }

    public function store(Request $request): RedirectResponse
    {
        $this->cleanRequest($request);

        $request->validate([
            'cliente_id' => ['required', 'string'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $cliente = Cliente::where('id_cliente', $request->cliente_id)->firstOrFail();

        if (User::where('cli_id', $cliente->id_cliente)->exists()) {
            return back()->withErrors([
                'cliente_id' => 'Este cliente ya tiene un usuario registrado.',
            ]);
        }

        $user = User::create([
            'name'     => trim($cliente->cli_nombre),
            'email'    => trim($cliente->cli_mail),
            'password' => Hash::make($request->password),
            'cli_id'   => $cliente->id_cliente,
        ]);

        event(new Registered($user));
        Auth::login($user->fresh());
        $request->session()->regenerate();


        return redirect(RouteServiceProvider::HOME);
    }

    public function storeFull(Request $request): RedirectResponse
    {
        $this->cleanRequest($request);

        $request->validate([
            'cli_ruc_ced' => [
                'required',
                'regex:/^\d{10}$|^\d{13}$/'
            ],
            'cli_nombre'    => ['required', 'string', 'max:255'],
            'cli_mail'      => ['required', 'email', 'max:100', 'unique:users,email'],
            'cli_telefono'  => ['nullable', 'string', 'size:10'],
            'cli_celular'   => ['nullable', 'string', 'size:10'],
            'cli_direccion' => ['required', 'string', 'max:255'],
            'id_ciudad'     => ['required', 'string', 'size:3', 'exists:ciudades,id_ciudad'],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        DB::transaction(function () use ($request) {

            // 🔥 USAR LA LÓGICA DE NEGOCIO CORRECTA
            $cliente = Cliente::createCliente([
                'cli_ruc_ced'   => $request->cli_ruc_ced,
                'cli_nombre'    => $request->cli_nombre,
                'cli_mail'      => $request->cli_mail,
                'cli_telefono'  => $request->cli_telefono,
                'cli_celular'   => $request->cli_celular,
                'cli_direccion' => $request->cli_direccion,
                'id_ciudad'     => $request->id_ciudad,
            ]);

            if (!$cliente) {
                abort(409, 'El cliente ya existe.');
            }

            $user = User::create([
                'name'     => $cliente->cli_nombre,
                'email'    => $cliente->cli_mail,
                'password' => Hash::make($request->password),
                'cli_id'   => $cliente->id_cliente,
            ]);

            event(new Registered($user));
            Auth::login($user->fresh());
            $request->session()->regenerate();

        });

        return redirect(RouteServiceProvider::HOME);
    }
}
