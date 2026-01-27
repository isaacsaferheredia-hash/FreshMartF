<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim($request->q);      // buscador
        $orden  = $request->orden;        // ordenar por

        $products = Producto::where('estado_prod', 'ACT')

            // 🔍 BUSCADOR
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('pro_nombre', 'ILIKE', "%{$buscar}%")
                        ->orWhere('pro_marca', 'ILIKE', "%{$buscar}%")
                        ->orWhere('id_producto', 'ILIKE', "%{$buscar}%");
                });
            })

            // 🔽 ORDENAMIENTO
            ->when($orden, function ($query) use ($orden) {
                match ($orden) {
                    'precio_asc'  => $query->orderBy('pro_precio_venta', 'asc'),
                    'precio_desc' => $query->orderBy('pro_precio_venta', 'desc'),
                    default       => $query->orderBy('id_producto', 'desc'),
                };
            }, function ($query) {
                $query->orderBy('id_producto', 'desc');
            })

            // 📄 PAGINACIÓN
            ->paginate(12)
            ->appends($request->query()); // mantiene q y orden

        return view('tienda', compact('products', 'buscar', 'orden'));
    }
}
