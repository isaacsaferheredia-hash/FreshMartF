<x-adminlayout title="Detalle del Producto">

    <div class="container mt-4">

        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-box"></i> Información del Producto
                </h5>
            </div>

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>ID:</strong>
                        <div class="form-control bg-light">
                            {{ $producto->id_producto }}
                        </div>
                    </div>

                    <div class="col-md-8">
                        <strong>Descripción:</strong>
                        <div class="form-control bg-light">
                            {{ $producto->pro_descripcion }}
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Tipo:</strong>
                        <div class="form-control bg-light">
                            {{ $producto->id_tipo }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <strong>UM Compra:</strong>
                        <div class="form-control bg-light">
                            {{ $producto->pro_um_compra }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <strong>UM Venta:</strong>
                        <div class="form-control bg-light">
                            {{ $producto->pro_um_venta }}
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Valor Compra:</strong>
                        <div class="form-control bg-light">
                            $ {{ number_format($producto->pro_valor_compra, 2) }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <strong>Precio Venta:</strong>
                        <div class="form-control bg-light">
                            $ {{ number_format($producto->pro_precio_venta, 2) }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <strong>Saldo Inicial:</strong>
                        <div class="form-control bg-light">
                            {{ $producto->pro_saldo_inicial }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <strong>Saldo Final:</strong>
                        <div class="form-control bg-light">
                            {{ $producto->pro_saldo_final }}
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong>Qty Ingresos:</strong>
                        <div class="form-control bg-light">
                            {{ $producto->pro_qty_ingresos }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <strong>Qty Egresos:</strong>
                        <div class="form-control bg-light">
                            {{ $producto->pro_qty_egresos }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <strong>Qty Ajustes:</strong>
                        <div class="form-control bg-light">
                            {{ $producto->pro_qty_ajustes }}
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <strong>Estado:</strong><br>
                    <span class="badge
                        {{ $producto->estado_prod === 'ACT'
                            ? 'bg-success'
                            : 'bg-danger' }}">
                        {{ $producto->estado_prod }}
                    </span>
                </div>

                {{-- ACCIONES --}}
                <div class="mt-3 d-flex gap-2">

                    {{-- VOLVER --}}
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>

                    {{-- EDITAR (AUXILIAR / ADMIN) --}}
                    @if(
                        auth()->user()->puede('INVENTARIO', 'CREAR')
                        && $producto->estado_prod === 'ACT'
                    )
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                    @endif

                </div>

            </div>
        </div>

    </div>

</x-adminlayout>
