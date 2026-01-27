<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ClienteFacturaController extends Controller
{
    // 📄 Listado de facturas del cliente
    public function index()
    {
        $cliId = trim(auth()->user()->cli_id);

        $facturas = DB::table('facturas')
            ->whereRaw('RTRIM(id_cliente) = ?', [$cliId])
            ->orderByDesc('fac_fecha_hora')
            ->get();

        return view('ClienteFacturas.index', compact('facturas'));
    }

    // 🔍 Detalle de factura
    public function show($id)
    {
        $cliId = trim(auth()->user()->cli_id);

        $factura = DB::table('facturas')
            ->where('id_factura', $id)
            ->whereRaw('RTRIM(id_cliente) = ?', [$cliId])
            ->first();

        if (!$factura) {
            abort(404);
        }

        $detalles = DB::table('proxfac')
            ->join('productos', 'productos.id_producto', '=', 'proxfac.id_producto')
            ->where('id_factura', $id)
            ->get();

        return view('ClienteFacturas.show', compact('factura', 'detalles'));
    }
}
