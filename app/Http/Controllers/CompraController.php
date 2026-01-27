<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedor;
use App\Models\ProductModel;
use App\Models\ProxRec;
use App\Models\Recepcion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $query = Compra::whereIn('estado_oc', ['APR', 'ANU']);

        if ($request->filled('q')) {
            $q = trim($request->q);

            if ($q === '') {
                return redirect()
                    ->route('compras.index')
                    ->with('error', 'Debe ingresar un criterio de búsqueda.');
            }

            $query->where(function ($sub) use ($q) {
                $sub->where('id_compra', 'ILIKE', "%{$q}%")
                    ->orWhere('id_proveedor', 'ILIKE', "%{$q}%");
            });
        }

        $compras = $query
            ->orderByDesc('oc_fecha_hora')
            ->paginate(10)
            ->withQueryString();

        $mensajeVacio = ($compras->total() === 0 && !$request->filled('q'))
            ? 'No existen órdenes de compra registradas.'
            : null;

        $mensajeSinResultados = ($compras->total() === 0 && $request->filled('q'))
            ? 'No se encontraron órdenes de compra con el criterio ingresado.'
            : null;

        return view(
            'compras.index',
            compact('compras', 'mensajeVacio', 'mensajeSinResultados')
        );
    }

    public function create()
    {
        $proveedores = Proveedor::where('estado_prv', 'ACT')
            ->orderBy('prv_nombre')
            ->get();

        $productos = ProductModel::where('estado_prod', 'ACT')
            ->orderBy('pro_descripcion')
            ->get();

        return view('compras.create', compact('proveedores', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proveedor'        => 'required|exists:proveedores,id_proveedor',
            'oc_fecha_hora'       => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.id_producto' => 'required|string|max:15',
            'items.*.cantidad'    => 'required|integer|min:1',
            'items.*.costo'       => 'required|numeric|min:0',
        ]);

        try {
            DB::statement(
                'CALL crear_compra_recepcion_y_asiento(?, ?, ?, ?)',
                [
                    $request->id_proveedor,
                    $request->oc_fecha_hora,
                    auth()->user()->cli_id ?? 'admin',
                    json_encode($request->items),
                ]
            );

            return redirect()
                ->route('compras.index')
                ->with('success', 'Compra y recepción creadas correctamente.');

        } catch (\Throwable $e) {
            dd($e->getMessage());
        }

           // return back()
            //    ->withInput()
         //       ->with('error', 'Error al registrar la compra y su recepción.');
       // }
    }
    public function show($id)
    {
        $compra = Compra::getCompraById($id);

        $productos = ProductModel::whereIn(
            'id_producto',
            $compra->detalles->pluck('id_producto')->unique()
        )
            ->get()
            ->keyBy('id_producto');

        $total = ($compra->oc_subtotal ?? 0) + ($compra->oc_iva ?? 0);

        return view('compras.show', compact('compra', 'productos', 'total'));
    }

    public function edit($id)
    {
        $compra = Compra::getCompraById($id);

        if ($compra->estado_oc === 'ANU') {
            return redirect()
                ->route('compras.index')
                ->with('warning', 'No se puede modificar una orden anulada.');
        }

        $proveedores = Proveedor::where('estado_prv', 'ACT')
            ->orderBy('prv_nombre')
            ->get();

        $productos = ProductModel::where('estado_prod', 'ACT')
            ->orderBy('pro_descripcion')
            ->get();

        $soloLectura = false;

        return view(
            'compras.edit',
            compact('compra', 'soloLectura', 'proveedores', 'productos')
        );
    }

    public function update(Request $request, $id)
    {
        $compra = Compra::getCompraById($id);

        if ($compra->estado_oc === 'ANU') {
            return redirect()
                ->route('compras.index')
                ->with('warning', 'No se puede modificar una orden anulada.');
        }

        $request->validate(
            [
                'id_proveedor'   => 'required|exists:proveedores,id_proveedor',
                'oc_fecha_hora'  => 'required|date',
                'oc_observacion' => 'nullable|string|max:255',

                'items'               => 'required|array|min:1',
                'items.*.id_producto' => 'required|string|max:15',
                'items.*.cantidad'    => 'required|integer|min:1',
                'items.*.costo'       => 'required|numeric|min:0',
            ],
            [
                'id_proveedor.required' => 'Debe seleccionar un proveedor.',
                'oc_fecha_hora.required' => 'La fecha de la orden es obligatoria.',
                'items.required' => 'Debe agregar al menos un producto.',
                'items.min'      => 'Debe agregar al menos un producto.',
            ]
        );

        $prov = Proveedor::where('id_proveedor', $request->id_proveedor)->first();
        if (!$prov || $prov->estado_prv !== 'ACT') {
            return back()
                ->withInput()
                ->with('error', 'No se pudo modificar la orden de compra.');
        }

        try {
            // 🔑 Lógica delegada al MODEL
            Compra::updateCompraConDetalle($compra, $request->all());
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'No se pudo modificar la orden de compra.');
        }

        return redirect()
            ->route('compras.index')
            ->with('success', 'Orden de compra actualizada correctamente.');
    }

    public function destroy($id)
    {
        $compra = Compra::findOrFail($id);

        if ($compra->estado_oc === 'ANU') {
            return redirect()
                ->route('compras.index')
                ->with('warning', 'La orden seleccionada ya se encuentra anulada.');
        }

        try {
            $compra->update(['estado_oc' => 'ANU']);
            $compra->detalles()->update(['estado_pxoc' => 'ANU']);
        } catch (\Throwable $e) {
            return redirect()
                ->route('compras.index')
                ->with('error', 'No se pudo anular la orden de compra.');
        }

        return redirect()
            ->route('compras.index')
            ->with('success', 'Orden de compra anulada correctamente.');
    }
}
