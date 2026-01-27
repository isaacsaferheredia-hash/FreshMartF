<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CarritoController extends Controller
{
    /**
     * Mostrar el carrito activo del usuario autenticado
     * GET /carrito
     */
    public function index()
    {
        $cliId = trim(auth()->user()->cli_id);

        $carrito = Carrito::carritoActivo($cliId);

        $detalles = [];

        if ($carrito) {
            $detalles = \DB::table('carrito_detalle as cd')
                ->join(
                    'productos as p',
                    \DB::raw('TRIM(cd.pro_id)'),
                    '=',
                    \DB::raw('TRIM(p.id_producto)')
                )
                ->where('cd.car_id', $carrito->car_id)
                ->select(
                    'cd.car_det_id',
                    'cd.cantidad',
                    'cd.precio_unit',
                    'cd.subtotal',
                    'p.pro_descripcion',
                    'p.pro_img',
                    'p.pro_saldo_final' // 👈 ESTA ES LA CLAVE
                )
                ->get();

        }

        return view('carrito.index', compact('carrito', 'detalles'));
    }


    /**
     * Agregar un producto al carrito
     * POST /carrito
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'ok'  => false,
                'msg' => 'Sesión no detectada'
            ], 401);
        }

        $request->validate([
            'pro_id'   => 'required',
            'cantidad' => 'required|integer|min:1'
        ]);

        $cliId      = auth()->user()->cli_id;
        $idProducto = $request->pro_id;

        // Obtener carrito activo
        $carrito = Carrito::obtenerActivo($cliId, $request);

        // Producto
        $producto = Producto::where('id_producto', $idProducto)->firstOrFail();

        // Agregar producto
        $carrito->agregarProducto($producto, $request->cantidad);

        // Total actualizado del carrito
        $cartCount = (int) $carrito->detalles()->sum('cantidad');

        // Respuesta JSON (AJAX)
        return response()->json([
            'ok'        => true,
            'msg'       => 'Producto agregado al carrito',
            'cartCount' => $cartCount
        ]);
    }

    /**
     * Actualizar la cantidad de un producto del carrito
     * PUT /carrito/{carDetId}
     */
    public function update(Request $request, $carDetId)
    {
        $request->validate([
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $cliId = auth()->user()->cli_id;

        $carrito = Carrito::carritoActivo($cliId);

        if (!$carrito) {
            return response()->json([
                'error' => 'No existe un carrito activo.'
            ], 404);
        }

        // Actualizar cantidad (tu lógica existente)
        $detalle = $carrito->actualizarCantidad($carDetId, (int) $request->cantidad);

        // 🔁 Volver a cargar carrito con totales actualizados
        $carrito->refresh();

        // ✅ RESPUESTA JSON PARA EL FRONTEND
        return response()->json([
            'cantidad'          => $detalle->cantidad,
            'subtotal_producto' => $detalle->subtotal,
            'car_subtotal'      => $carrito->car_subtotal,
            'car_iva'           => $carrito->car_iva,
            'car_total'         => $carrito->car_total,
        ]);
    }

    /**
     * Eliminar un producto del carrito
     * DELETE /carrito/{carDetId}
     */
    public function destroy($carDetId)
    {
        $cliId = auth()->user()->cli_id;

        $carrito = Carrito::carritoActivo($cliId);

        if (!$carrito) {
            return redirect()->route('carrito.index')
                ->with('error', 'No existe un carrito activo.');
        }

        $carrito->eliminarDetalle($carDetId);

        return back();
    }

}
