<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\ProxFac;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $query = Factura::with('cliente')
            ->whereIn('estado_fac', ['ABI', 'APR', 'ANU']);

        if ($request->filled('q')) {
            $q = $request->q;

            $query->where(function ($sub) use ($q) {
                $sub->where('id_factura', 'ILIKE', "%{$q}%")
                    ->orWhereHas('cliente', function ($c) use ($q) {
                        $c->where('cli_nombre', 'ILIKE', "%{$q}%")
                            ->orWhere('cli_ruc_ced', 'ILIKE', "%{$q}%");
                    });
            });
        }

        $facturas = $query
            ->orderByDesc('fac_fecha_hora')
            ->paginate(10)
            ->withQueryString();

        return view('Facturas.index', compact('facturas'));
    }

    public function create()
    {
        $clientes = Cliente::where('estado_cli', 'ACT')->orderBy('cli_nombre')->get();
        $productos = Producto::where('estado_prod', 'ACT')->orderBy('pro_descripcion')->get();

        return view('facturas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'cliente_id' => 'required|exists:clientes,id_cliente',
                'productos'  => 'required|array|min:1',
                'productos.*.cantidad' => 'required|integer|min:1',
            ],
            [
                'cliente_id.required' => 'Debe seleccionar un cliente.',
                'productos.required'  => 'Debe agregar al menos un producto.',
            ]
        );

        DB::transaction(function () use ($request) {

            $factura = Factura::crearFactura($request->cliente_id);

            $subtotal = 0;

            foreach ($request->productos as $productoId => $item) {

                $producto = Producto::findOrFail($productoId);

                $precio   = $producto->pro_precio_venta;
                $cantidad = (int)$item['cantidad'];
                $sub      = $precio * $cantidad;

                ProxFac::create([
                    'id_factura'   => $factura->id_factura,
                    'id_producto'  => $producto->id_producto,
                    'pxf_cantidad' => $cantidad,
                    'pxf_precio'   => $precio,
                    'pxf_subtotal' => $sub,
                    'estado_pxf'   => 'ABI',
                ]);

                $subtotal += $sub;
            }

            Factura::actualizarTotales($factura->id_factura, $subtotal);
        });

        return redirect()
            ->route('facturas.index')
            ->with('success', 'Factura registrada correctamente.');
    }

    public function edit(Factura $factura)
    {
        if ($factura->estado_fac !== 'ABI') {
            return redirect()->route('facturas.index')
                ->with('warning', 'Solo se pueden modificar facturas ABIERTAS.');
        }

        $clientes  = Cliente::where('estado_cli', 'ACT')->orderBy('cli_nombre')->get();
        $productos = Producto::where('estado_prod', 'ACT')->orderBy('pro_descripcion')->get();

        return view('Facturas.edit', [
            'factura'  => $factura,
            'clientes' => $clientes,
            'productos'=> $productos,
            'detalles' => $factura->detalles
        ]);
    }

    public function update(Request $request, Factura $factura)
    {
        if ($factura->estado_fac !== 'ABI') {
            return redirect()->route('facturas.index')
                ->with('warning', 'No se puede modificar una factura cerrada.');
        }

        $request->validate(
            [
                'id_cliente'          => 'required|exists:clientes,id_cliente',
                'fac_fecha_hora'      => 'required|date',
                'items'               => 'required|array|min:1',
                'items.*.id_producto' => 'required|exists:productos,id_producto',
                'items.*.cantidad'    => 'required|numeric|min:1',
            ]
        );

        DB::transaction(function () use ($request, $factura) {

            ProxFac::where('id_factura', $factura->id_factura)->delete();

            $subtotal = 0;

            foreach ($request->items as $item) {

                $producto = Producto::findOrFail($item['id_producto']);
                $precio   = $producto->pro_precio_venta;
                $cantidad = (int)$item['cantidad'];
                $sub      = $precio * $cantidad;

                ProxFac::create([
                    'id_factura'   => $factura->id_factura,
                    'id_producto'  => $producto->id_producto,
                    'pxf_cantidad' => $cantidad,
                    'pxf_precio'   => $precio,
                    'pxf_subtotal' => $sub,
                    'estado_pxf'   => 'ABI',
                ]);

                $subtotal += $sub;
            }

            Factura::actualizarTotales($factura->id_factura, $subtotal);

            $factura->update([
                'id_cliente'     => $request->id_cliente,
                'fac_fecha_hora' => $request->fac_fecha_hora,
            ]);
        });

        return redirect()
            ->route('facturas.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    public function destroy(Factura $factura)
    {
        if ($factura->estado_fac !== 'ABI') {
            return redirect()->route('Facturas.index')
                ->with('warning', 'Solo se pueden anular facturas ABIERTAS.');
        }

        $factura->update(['estado_fac' => 'ANU']);

        ProxFac::where('id_factura', $factura->id_factura)
            ->update(['estado_pxf' => 'ANU']);

        return redirect()
            ->route('facturas.index')
            ->with('success', 'Factura anulada correctamente.');
    }

    public function show(Factura $factura)
    {
        $facturaId = trim($factura->id_factura);

        $detalles = ProxFac::query()
            ->join('productos', function ($join) {
                $join->on(
                    DB::raw('trim(proxfac.id_producto)'),
                    '=',
                    DB::raw('trim(productos.id_producto)')
                );
            })
            ->whereRaw('trim(proxfac.id_factura) = ?', [$facturaId])
            ->where('proxfac.estado_pxf', '!=', 'ANU')
            ->select([
                'proxfac.*',
                'productos.pro_descripcion',
                'productos.pro_precio_venta',
            ])
            ->get();

        return view('facturas.show', compact('factura', 'detalles'));
    }

    public function aprobar(string $idFactura)
    {
        try {

            DB::statement(
                'CALL aprobar_factura_sin_carrito_y_asiento(?)',
                [$idFactura]
            );

            return redirect()
                ->route('facturas.show', $idFactura)
                ->with('success', 'Factura aprobada correctamente.');

        } catch (\Throwable $e) {

            // 🔥 FORZAR VISUALIZACIÓN TOTAL DEL ERROR
            dd([
                'mensaje' => $e->getMessage(),
                'codigo'  => $e->getCode(),
                'archivo' => $e->getFile(),
                'linea'   => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }
    }

}
