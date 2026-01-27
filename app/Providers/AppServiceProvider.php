<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Carrito;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\Categories;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {

            // Categorías (se mantiene)
            $categories = Categories::getCategories();
            $view->with('categories', $categories);

            // CONTADOR REAL DEL CARRITO (DESDE BD)
            $cartCount = 0;

            if (Auth::check()) {
                $carrito = Carrito::where('cli_id', Auth::user()->cli_id)
                    ->where('car_estado', 'ABI') // activo
                    ->with('detalles')
                    ->first();

                if ($carrito) {
                    $cartCount = $carrito->detalles->sum('cantidad');
                }
            }

            $view->with('cartCount', $cartCount);
        });
    }
}

