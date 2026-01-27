<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\ProductModel;

class HomeController extends Controller
{
    public function index()
    {
        // Categorías
        $categories = Categories::getCategories();

        // Productos destacados
        $destacados = ProductModel::getDestacados();

        return view('home', compact('categories', 'destacados'));
    }
}
