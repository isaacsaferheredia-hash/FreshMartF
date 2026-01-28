<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categories;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /* =====================================================
       INDEX – listado
    ===================================================== */
    public function index(Request $request)
    {
        $query = Producto::whereIn('estado_prod', ['ACT', 'ANU']);

        if ($request->filled('q')) {
            $query->where('pro_descripcion', 'ILIKE', '%' . $request->q . '%');
        }

        $productos = $query
            ->orderBy('id_producto')
            ->paginate(10);

        return view('Productos.index', compact('productos'));
    }

    /* =====================================================
       CREATE – formulario nuevo
    ===================================================== */
    public function create()
    {
        $tipos = Categories::orderBy('id_tipo')->get();
        $unidades = UnidadMedida::orderBy('um_descripcion')->get();

        return view('Productos.Create', compact('tipos', 'unidades'));
    }

    /* =====================================================
       STORE – guardar nuevo
    ===================================================== */
    public function store(Request $request)
    {
        // Normalizar CHAR / espacios
        $request->merge([
            'id_tipo'       => trim($request->id_tipo),
            'pro_um_compra' => trim($request->pro_um_compra),
            'pro_um_venta'  => trim($request->pro_um_venta),
            'dest'          => trim($request->dest),
        ]);

        $request->validate([
            'pro_descripcion'  => 'required|string|max:255|unique:productos,pro_descripcion',
            'id_tipo'          => 'required',
            'pro_um_compra'    => 'required',
            'pro_um_venta'     => 'required',
            'pro_valor_compra' => 'required|numeric|min:0',
            'pro_precio_venta' => 'required|numeric|min:0',
            'dest'             => 'required|in:S,N',
        ]);

        Producto::crearProducto([
            'pro_descripcion'   => $request->pro_descripcion,
            'id_tipo'           => $request->id_tipo,
            'pro_um_compra'     => $request->pro_um_compra,
            'pro_um_venta'      => $request->pro_um_venta,
            'pro_valor_compra'  => $request->pro_valor_compra,
            'pro_precio_venta'  => $request->pro_precio_venta,
            'pro_saldo_inicial' => $request->pro_saldo_inicial ?? 0,
            'dest'              => $request->dest,
        ]);

        return redirect()
            ->route('Productos.index')
            ->with('success', 'Producto registrado correctamente');
    }

    /* =====================================================
       SHOW – ver detalle
    ===================================================== */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);

        return view('Productos.show', compact('producto'));
    }

    /* =====================================================
       EDIT – formulario edición
    ===================================================== */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);

        $soloLectura = $producto->estado_prod === 'ANU';

        $tipos = Categories::orderBy('id_tipo')->get();
        $unidades = UnidadMedida::orderBy('um_descripcion')->get();

        return view('Productos.Edit', compact(
            'producto',
            'tipos',
            'unidades',
            'soloLectura'
        ));
    }

    /* =====================================================
       UPDATE – guardar edición
    ===================================================== */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        if ($producto->estado_prod === 'ANU') {
            return redirect()
                ->route('Productos.index')
                ->with('warning', 'No se puede modificar un producto inactivo.');
        }

        $request->merge([
            'id_tipo'       => trim($request->id_tipo),
            'pro_um_compra' => trim($request->pro_um_compra),
            'pro_um_venta'  => trim($request->pro_um_venta),
            'dest'          => trim($request->dest),
        ]);

        $request->validate([
            'pro_descripcion'  => 'required|string|max:255|unique:productos,pro_descripcion,' . $producto->id_producto . ',id_producto',
            'id_tipo'          => 'required',
            'pro_um_compra'    => 'required',
            'pro_um_venta'     => 'required',
            'pro_valor_compra' => 'required|numeric|min:0',
            'pro_precio_venta' => 'required|numeric|min:0',
            'dest'             => 'required|in:S,N',
        ]);

        $producto->actualizarProducto([
            'pro_descripcion'  => $request->pro_descripcion,
            'id_tipo'          => $request->id_tipo,
            'pro_um_compra'    => $request->pro_um_compra,
            'pro_um_venta'     => $request->pro_um_venta,
            'pro_valor_compra' => $request->pro_valor_compra,
            'pro_precio_venta' => $request->pro_precio_venta,
            'dest'             => $request->dest,
        ]);

        return redirect()
            ->route('Productos.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    /* =====================================================
       DESTROY – anular
    ===================================================== */
    public function destroy($id)
    {
        Producto::findOrFail($id)->desactivar();

        return redirect()
            ->route('Productos.index')
            ->with('success', 'Producto desactivado correctamente');
    }


    public function detalle(string $id)
    {
        $producto = Producto::whereRaw('TRIM(id_producto) = ?', [trim($id)])
            ->firstOrFail();

        return view('Productos.detalle', compact('producto'));
    }


    public function buscar(Request $request)
    {
        $q = trim($request->get('q'));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $productos = \App\Models\Producto::where('estado_prod', 'ACT')
            ->where('pro_descripcion', 'ILIKE', "%{$q}%")
            ->limit(6)
            ->get([
                'id_producto',
                'pro_descripcion',
                'pro_precio_venta',
                'pro_img'
            ]);

        return response()->json($productos);
    }

}
