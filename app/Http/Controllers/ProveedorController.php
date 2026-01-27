<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Ciudad;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $proveedores = Proveedor::listar($request->q);
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        $ciudades = Ciudad::orderBy('ciu_descripcion')->get();
        return view('proveedores.create', compact('ciudades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prv_nombre'    => 'required|string|max:40',
            'prv_ruc_ced'   => 'required|string|max:13|unique:proveedores,prv_ruc_ced',
            'prv_mail'      => 'required|email|max:60',
            'id_ciudad'     => 'required',
            'prv_direccion' => 'required|string|max:60',
        ]);

        Proveedor::crear($request->all());

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor creado correctamente.');
    }

    public function show(Proveedor $proveedor)
    {
        $proveedor->load('ciudad');
        return view('proveedores.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        $ciudades = Ciudad::orderBy('ciu_descripcion')->get();
        return view('proveedores.edit', compact('proveedor', 'ciudades'));
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'prv_nombre'    => 'required|string|max:40',
            'prv_ruc_ced'   => 'required|string|max:13',
            'prv_mail'      => 'required|email|max:60',
            'id_ciudad'     => 'required',
            'prv_direccion' => 'required|string|max:60',
        ]);

        Proveedor::actualizar($proveedor, $request->all());

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        Proveedor::desactivar($proveedor);

        return redirect()
            ->route('proveedores.index')
            ->with('warning', 'Proveedor desactivado.');
    }
}
