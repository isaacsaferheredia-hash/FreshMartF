<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use App\Models\ProxRec;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecepcionController extends Controller
{
    /* =====================================================
       INDEX
    ===================================================== */
    public function index(Request $request)
    {
        $query = Recepcion::whereIn('estado_rec', ['ABI', 'APR', 'ANU']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('id_recibo', 'ILIKE', "%{$q}%")
                ->orWhere('rec_descripcion', 'ILIKE', "%{$q}%");
        }

        $recepciones = $query
            ->orderByDesc('rec_fechahora')
            ->paginate(10)
            ->withQueryString();

        return view('recepciones.index', compact('recepciones'));
    }

    /* =====================================================
       CREATE
    ===================================================== */
    public function create()
    {
        $productos = Producto::where('estado_prod', 'ACT')
            ->orderBy('pro_descripcion')
            ->get();

        return view('recepciones.create', compact('productos'));
    }

    /* =====================================================
       STORE
    ===================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'rec_descripcion'        => 'required|string|max:30', // 🔴 CLAVE
            'items'                 => 'required|array|min:1',
            'items.*.id_producto'   => 'required|string|max:15',
            'items.*.cantidad'      => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {

            $recepcion = Recepcion::crearRecepcion(
                trim($request->rec_descripcion),
                count($request->items)
            );

            foreach ($request->items as $item) {
                ProxRec::create([
                    'id_recibo'        => $recepcion->id_recibo,
                    'id_producto'      => trim($item['id_producto']),
                    'pxr_cantidad'     => (int) $item['cantidad'],
                    'pxr_qty_recibida' => 0,
                    'estado_pxr'       => 'ABI',
                    'trial866'         => 'T',
                ]);
            }
        });

        return redirect()
            ->route('recepciones.index')
            ->with('success', 'Recepción registrada correctamente');
    }

    /* =====================================================
       SHOW  ✅ (SIN ROUTE MODEL BINDING)
    ===================================================== */
    public function show($recepcione)
    {
        $recepcion = Recepcion::where('id_recibo', $recepcione)->firstOrFail();

        $detalles = ProxRec::query()
            ->where('proxrec.id_recibo', $recepcion->id_recibo)
            ->whereRaw("TRIM(proxrec.id_producto) <> '0'")
            ->join(
                'productos',
                DB::raw('TRIM(productos.id_producto)'),
                '=',
                DB::raw('TRIM(proxrec.id_producto)')
            )
            ->select(
                'proxrec.*',
                'productos.pro_descripcion'
            )
            ->get();

        return view('recepciones.show', compact('recepcion', 'detalles'));
    }



    /* =====================================================
       EDIT
    ===================================================== */
    public function edit($recepcione)
    {
        $recepcion = Recepcion::where('id_recibo', $recepcione)->firstOrFail();

        if ($recepcion->estado_rec !== 'ABI') {
            return redirect()
                ->route('recepciones.index')
                ->with('warning', 'Solo se pueden editar recepciones ABIERTAS.');
        }

        $detalles = ProxRec::query()
            ->join('productos', function ($join) {
                $join->on('productos.id_producto', '=', 'proxrec.id_producto');
            })
            ->where('proxrec.id_recibo', $recepcion->id_recibo)
            ->where('proxrec.estado_pxr', '!=', 'ANU')
            ->whereRaw("TRIM(proxrec.id_producto) <> '0'")
            ->select(
                'proxrec.id_recibo',
                'proxrec.id_producto',
                'proxrec.pxr_cantidad',
                'proxrec.pxr_qty_recibida',
                'proxrec.estado_pxr',
                'productos.pro_descripcion'
            )
            ->get();

        return view('recepciones.edit', compact('recepcion', 'detalles'));
    }



    /* =====================================================
       UPDATE
    ===================================================== */
    public function update(Request $request, $recepcione)
    {
        $recepcion = Recepcion::where('id_recibo', $recepcione)->firstOrFail();

        // 🔒 Solo recepciones ABI
        if ($recepcion->estado_rec !== 'ABI') {
            return redirect()
                ->route('recepciones.index')
                ->with('warning', 'No se puede modificar una recepción cerrada.');
        }

        // 🔍 Validación básica de formulario
        $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.id_producto'  => 'required|exists:productos,id_producto',
            'items.*.qty_recibida' => 'required|integer|min:0',
        ]);

        try {

            DB::transaction(function () use ($request, $recepcion) {

                /* =====================================================
                   1️⃣ GUARDAR CANTIDADES RECIBIDAS (SIN APROBAR)
                ===================================================== */
                foreach ($request->items as $item) {

                    $detalle = ProxRec::where('id_recibo', $recepcion->id_recibo)
                        ->where('id_producto', $item['id_producto'])
                        ->where('estado_pxr', '!=', 'ANU')
                        ->firstOrFail();

                    // ⚠️ No se valida aquí igualdad
                    // La validación FUERTE la hace el procedure
                    $detalle->update([
                        'pxr_qty_recibida' => (int) $item['qty_recibida'],
                    ]);
                }

                /* =====================================================
                   2️⃣ APROBAR RECEPCIÓN (PROCEDURE EN BD)
                ===================================================== */
                DB::statement(
                    'CALL aprobar_recepcion(?)',
                    [$recepcion->id_recibo]
                );
            });

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('recepciones.show', $recepcion->id_recibo)
            ->with('success', 'Recepción aprobada correctamente');
    }




    /* =====================================================
       DESTROY
    ===================================================== */
    public function destroy($recepcione)
    {
        $recepcion = Recepcion::where('id_recibo', $recepcione)->firstOrFail();

        if ($recepcion->estado_rec !== 'ABI') {
            return redirect()
                ->route('recepciones.index')
                ->with('warning', 'Solo se pueden anular recepciones ABIERTAS.');
        }

        $recepcion->update(['estado_rec' => 'ANU']);

        ProxRec::where('id_recibo', $recepcion->id_recibo)
            ->update(['estado_pxr' => 'ANU']);

        return redirect()
            ->route('recepciones.index')
            ->with('success', 'Recepción anulada correctamente');
    }
}
