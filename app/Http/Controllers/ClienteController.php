<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Ciudad;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::whereIn('estado_cli', ['ACT', 'INA']);

        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($sub) use ($q) {
                $sub->where('id_cliente', 'ILIKE', "%{$q}%")
                    ->orWhere('cli_ruc_ced', 'ILIKE', "%{$q}%")
                    ->orWhere('cli_nombre', 'ILIKE', "%{$q}%");
            });
        }

        $clientes = $query
            ->orderBy('cli_nombre')
            ->paginate(10)
            ->withQueryString();

        return view('Clientes.index', compact('clientes'));
    }

    public function create()
    {
        $ciudades = Ciudad::orderBy('ciu_descripcion')->get();

        return view('Clientes.create', compact('ciudades'));
    }

    /**
     * ALGORITMO
     * PREEREQUISITOS
     * 1. request Cliente
     * PASOS o LOGICA DEL NEGOCIO
     * 1. Validar la informacion que llega
     * 2. Llamar a la funcion create cliente para guardar en BD
     * 3. Mostrar la lista de clientes a traves de blade
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validar la informacion que llega
        $request->validate(
            [
                'cli_nombre'    => 'required|string|max:40',
                'cli_ruc_ced'   => 'required|string|max:13',
                'cli_telefono'  => 'nullable|digits:10',
                'cli_celular'   => 'nullable|digits:10',
                'cli_mail'      => 'required|string|max:60|regex:/@/',
                'id_ciudad'     => 'required|exists:ciudades,id_ciudad',
                'cli_direccion' => 'required|string',
            ],
            [
                'cli_nombre.required'    => 'El nombre es obligatorio.',
                'cli_ruc_ced.required'   => 'El RUC o cédula es obligatorio.',
                'cli_mail.required'      => 'El correo electrónico es obligatorio.',
                'id_ciudad.required'     => 'Debe seleccionar una ciudad.',
                'cli_direccion.required' => 'La dirección es obligatoria.',
            ]
        );

        try {
            // 2. llamar a la funcion createCliente para almacenar en la BD
            Cliente::createCliente($request->all());
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'No se pudo registrar el cliente.');
        }

        // 3. mmm
        return redirect()
            ->route('Clientes.index')
            ->with('success', 'Cliente registrado correctamente');
    }

    public function edit(Cliente $cliente)
    {
        if ($cliente->estado_cli === 'INA') {
            return redirect()
                ->route('clientes.index')
                ->with('warning', 'No se puede modificar un cliente inactivo.');
        }

        $ciudades = Ciudad::orderBy('ciu_descripcion')->get();

        return view('Clientes.edit', compact('cliente', 'ciudades'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        if ($cliente->estado_cli === 'INA') {
            return redirect()
                ->route('Clientes.index')
                ->with('warning', 'No se puede modificar un cliente inactivo.');
        }

        $request->validate(
            [
                'cli_nombre'    => 'required|string|max:40',
                'cli_ruc_ced'   => 'required|string|max:13',
                'cli_telefono'  => 'nullable|digits:10',
                'cli_celular'   => 'nullable|digits:10',
                'cli_mail'      => 'required|string|max:60|regex:/@/',
                'id_ciudad'     => 'required|exists:ciudades,id_ciudad',
                'cli_direccion' => 'required|string|max:60',
            ]
        );

        try {
            Cliente::updateCliente($cliente, $request->all());
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el cliente.');
        }

        return redirect()
            ->route('Clientes.index')
            ->with('success', 'Cliente actualizado correctamente');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->estado_cli === 'INA') {
            return redirect()
                ->route('Clientes.index')
                ->with('warning', 'No se puede eliminar un cliente inactivo.');
        }

        Cliente::desactivarCliente($cliente);

        return redirect()
            ->route('Clientes.index')
            ->with('success', 'Cliente desactivado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        return view('Clientes.show', compact('cliente'));
    }
}
