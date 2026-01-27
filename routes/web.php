<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\UsuarioPermisoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ClienteFacturaController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);
Route::view('/contacto', 'contacto')->name('contacto');
Route::view('/faq', 'faq')->name('faq');
Route::view('/devoluciones', 'devoluciones')->name('devoluciones');
Route::view('/envios', 'envios')->name('envios');
Route::view('/quienes-somos', 'quienes-somos')->name('quienes.somos');
Route::view('/ubicaciones', 'ubicaciones')->name('ubicaciones');


Route::view('/blog', 'blog')->name('blog');
Route::view('/blog/recetas-saludables', 'blog.recetas')->name('blog.recetas');
Route::view('/blog/como-elegir-frutas', 'blog.frutas')->name('blog.frutas');
Route::view('/blog/nuevos-productos', 'blog.novedades')->name('blog.novedades');


Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda');
Route::get('/categoria/{id_tipo}', [CategoriaController::class, 'show'])->name('categoria.show');



require __DIR__ . '/auth.php';



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-auth', function () {
        return view('dashboard');
    })->name('dashboard.auth');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito', [CarritoController::class, 'store'])->name('carrito.store');
    Route::put('/carrito/{carDetId}', [CarritoController::class, 'update'])->name('carrito.update');
    Route::delete('/carrito/{carDetId}', [CarritoController::class, 'destroy'])->name('carrito.destroy');

    Route::middleware(['auth'])->group(function () {

        // Dashboard (SIN control por área)
        Route::get('/homeback', [DashboardController::class, 'index'])
            ->name('homeback');

        // =======================
        // VENTAS
        // =======================
        Route::middleware('rol:VENTAS,ADMIN,JEFE,AUXILIAR')->group(function () {
            Route::resource('clientes', ClienteController::class);
            Route::resource('facturas', FacturaController::class);
        });

        // =======================
        // INVENTARIO
        // =======================
        Route::middleware('rol:INVENTARIO,ADMIN,JEFE')->group(function () {
            Route::resource('productos', ProductoController::class);
            Route::resource('recepciones', RecepcionController::class);
        });

        // =======================
        // COMPRAS
        // =======================
        Route::middleware('rol:COMPRAS,ADMIN,JEFE')->group(function () {
            Route::resource('proveedores', ProveedorController::class);
            Route::resource('compras', CompraController::class);
        });

    });


    Route::get('/test-cli', function () {
        return auth()->user()->cli_id;
    });
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

});


Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index')
    ->middleware('auth');

Route::post('/checkout/pagar', [CheckoutController::class, 'pagar'])
    ->name('checkout.pagar')
    ->middleware('auth');

Route::get('/checkout/exito', [CheckoutController::class, 'exito'])
    ->name('checkout.exito')
    ->middleware('auth');

Route::middleware('auth')->group(function () {

    Route::get('/mis-facturas', [ClienteFacturaController::class, 'index'])
        ->name('ClienteFacturas.index');

    Route::get('/mis-facturas/{id}', [ClienteFacturaController::class, 'show'])
        ->name('ClienteFacturas.show');

});

Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register/check', [RegisteredUserController::class, 'checkCedula'])
        ->name('register.check');

    Route::get('/register/confirm', function () {
        return view('auth.register-confirm');
    })->name('register.confirm');

    Route::post('/register/confirm', [RegisteredUserController::class, 'confirmEmail'])
        ->name('register.confirm.post');

    Route::get('/register/full', [RegisteredUserController::class, 'showRegisterFull'])
        ->name('register.full');

    Route::post('/register/full', [RegisteredUserController::class, 'storeFull'])
        ->name('register.full.store');

    Route::post('/register/user', [RegisteredUserController::class, 'store'])
        ->name('register.user.store');
});

Route::get('/tienda/producto/{id}', [ProductoController::class, 'detalle'])
    ->name('producto.detalle');

Route::get('/buscar-productos', [ProductoController::class, 'buscar'])
    ->name('productos.buscar');

Route::get('/register/password', function () {
    if (!session()->has('cliente_id')) {
        return redirect()->route('register');
    }

    return view('auth.register-user', [
        'cliente_id' => session('cliente_id'),
    ]);
})->name('register.password');


Route::get('/producto/stock/{id}', function ($id) {
    $stock = DB::table('productos')
        ->where('id_producto', $id)
        ->value('pro_saldo_final');

    return response()->json([
        'stock' => (int) $stock
    ]);
});


Route::post(
    '/facturas/{idFactura}/aprobar',
    [FacturaController::class, 'aprobar']
)->name('facturas.aprobar');



Route::get('/admin/login', [AdminLoginController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminLoginController::class, 'login'])
    ->name('admin.login.submit');

Route::post('/admin/logout', [AdminLoginController::class, 'logout'])
    ->name('admin.logout');


Route::get('/admin', function () {
    return 'Admin OK';
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::resource('usuarios', UsuarioController::class);
    });

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get(
            'usuarios/{user}/permisos',
            [UsuarioPermisoController::class, 'edit']
        )->name('usuarios.permisos.edit');

        Route::post(
            'usuarios/{user}/permisos',
            [UsuarioPermisoController::class, 'update']
        )->name('usuarios.permisos.update');
    });


Route::delete('usuarios/{user}', [UsuarioController::class, 'destroy'])
    ->name('usuarios.destroy');
