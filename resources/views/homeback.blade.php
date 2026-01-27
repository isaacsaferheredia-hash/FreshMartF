<x-adminlayout title="Inicio - Panel Administrativo">

    <div class="mb-4 text-center">
        <h2 class="fw-bold mb-1">Panel Administrativo</h2>
        <p class="text-muted mb-0">
            Seleccione un módulo para comenzar
        </p>
    </div>

    <div class="row g-4 justify-content-center">

        {{-- =========================
           ÁREA: VENTAS
           Clientes + Facturas
        ========================= --}}
        @if(auth()->user()->tieneArea('VENTAS'))

            {{-- CLIENTES --}}
            <div class="col-md-3">
                <a href="{{ route('clientes.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 h-100 text-center">
                        <div class="card-body py-4">
                            <i class="fa-solid fa-users fa-3x text-primary mb-3"></i>
                            <h5 class="fw-semibold">Clientes</h5>
                        </div>
                    </div>
                </a>
            </div>

            {{-- FACTURAS --}}
            <div class="col-md-3">
                <a href="{{ route('facturas.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 h-100 text-center">
                        <div class="card-body py-4">
                            <i class="fa-solid fa-file-invoice-dollar fa-3x text-success mb-3"></i>
                            <h5 class="fw-semibold">Facturas</h5>
                        </div>
                    </div>
                </a>
            </div>

        @endif

        {{-- =========================
           ÁREA: INVENTARIO
           Productos + Recepciones
        ========================= --}}
        @if(auth()->user()->tieneArea('INVENTARIO'))

            {{-- PRODUCTOS --}}
            <div class="col-md-3">
                <a href="{{ route('productos.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 h-100 text-center">
                        <div class="card-body py-4">
                            <i class="fa-solid fa-box fa-3x text-warning mb-3"></i>
                            <h5 class="fw-semibold">Productos</h5>
                        </div>
                    </div>
                </a>
            </div>

            {{-- RECEPCIONES --}}
            <div class="col-md-3">
                <a href="{{ route('recepciones.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 h-100 text-center">
                        <div class="card-body py-4">
                            <i class="fa-solid fa-warehouse fa-3x text-danger mb-3"></i>
                            <h5 class="fw-semibold">Recepciones</h5>
                        </div>
                    </div>
                </a>
            </div>

        @endif

        {{-- =========================
           ÁREA: COMPRAS
           Compras + Proveedores
        ========================= --}}
        @if(auth()->user()->tieneArea('COMPRAS'))

            {{-- COMPRAS --}}
            <div class="col-md-3">
                <a href="{{ route('compras.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 h-100 text-center">
                        <div class="card-body py-4">
                            <i class="fa-solid fa-cart-shopping fa-3x text-info mb-3"></i>
                            <h5 class="fw-semibold">Compras</h5>
                        </div>
                    </div>
                </a>
            </div>

            {{-- PROVEEDORES --}}
            <div class="col-md-3">
                <a href="{{ route('proveedores.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-0 h-100 text-center">
                        <div class="card-body py-4">
                            <i class="fa-solid fa-truck-field fa-3x text-secondary mb-3"></i>
                            <h5 class="fw-semibold">Proveedores</h5>
                        </div>
                    </div>
                </a>
            </div>

        @endif

    </div>

</x-adminlayout>
