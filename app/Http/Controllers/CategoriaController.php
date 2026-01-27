<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function show($id_tipo)
    {
        // Obtener info de la categoría
        $categoria = DB::table('tipos_producto')
            ->where('id_tipo', $id_tipo)
            ->first();

        // Productos de esa categoría
        $productos = DB::table('productos')
            ->where('estado_prod', 'ACT')
            ->where('id_tipo', $id_tipo)
            ->paginate(12);

        return view('categoria', compact('categoria', 'productos'));
    }
}
