<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Panel Administrativo' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="{{ route('homeback') }}">
            <i class="fa-solid fa-store me-2"></i> FreshMart Admin
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                {{-- INICIO --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('homeback') ? 'active' : '' }}"
                       href="{{ route('homeback') }}">
                        <i class="fa-solid fa-house me-1"></i> Inicio
                    </a>
                </li>

                {{-- =========================
                   ÁREA: VENTAS
                   Clientes + Facturas
                ========================= --}}
                @if(auth()->user()->tieneArea('VENTAS'))

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('clientes*') ? 'active' : '' }}"
                           href="{{ route('clientes.index') }}">
                            <i class="fa-solid fa-users me-1"></i> Clientes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('facturas*') ? 'active' : '' }}"
                           href="{{ route('facturas.index') }}">
                            <i class="fa-solid fa-file-invoice-dollar me-1"></i> Facturas
                        </a>
                    </li>

                @endif

                {{-- =========================
                   ÁREA: INVENTARIO
                   Productos + Recepciones
                ========================= --}}
                @if(auth()->user()->tieneArea('INVENTARIO'))

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('productos*') ? 'active' : '' }}"
                           href="{{ route('productos.index') }}">
                            <i class="fa-solid fa-box me-1"></i> Productos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('recepciones*') ? 'active' : '' }}"
                           href="{{ route('recepciones.index') }}">
                            <i class="fa-solid fa-warehouse me-1"></i> Recepciones
                        </a>
                    </li>

                @endif

                {{-- =========================
                   ÁREA: COMPRAS
                   Compras + Proveedores
                ========================= --}}
                @if(auth()->user()->tieneArea('COMPRAS'))

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('compras*') ? 'active' : '' }}"
                           href="{{ route('compras.index') }}">
                            <i class="fa-solid fa-cart-shopping me-1"></i> Compras
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('proveedores*') ? 'active' : '' }}"
                           href="{{ route('proveedores.index') }}">
                            <i class="fa-solid fa-truck-field me-1"></i> Proveedores
                        </a>
                    </li>

                @endif

            </ul>

            <a href="/home" class="btn btn-outline-light btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Sitio
            </a>
        </div>

    </div>
</nav>

<main class="container my-4">
    {{ $slot }}
</main>

<footer class="text-center text-muted small py-3">
    Panel Administrativo · FreshMart © 2025
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
