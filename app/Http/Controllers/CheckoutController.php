<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Factura;


class CheckoutController extends Controller
{
    public function index()
    {
        return view('Checkout.index');
    }

    public function pagar()
    {
        // 1️⃣ Datos base
        $cliId = trim(auth()->user()->cli_id);
        $userId = auth()->user()->id;

        // 2️⃣ Generar ID factura (TU función actual)
        $idFactura = Factura::generarId();

        try {
            // 3️⃣ Llamar al PROCEDURE en PostgreSQL
            DB::statement(
                'CALL pagar_carrito_contable(?, ?, ?)',
                [
                    $cliId,
                    $idFactura,
                    $userId
                ]
            );

            // 4️⃣ Éxito
            return redirect()->route('checkout.exito');

        } catch (\Throwable $e) {

            // 🔴 Si falla algo, lo ves claro
            return redirect()
                ->route('carrito.index')
                ->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    public function exito()
    {
        return view('checkout.exito');
    }
}
