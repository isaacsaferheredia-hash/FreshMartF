<x-adminlayout title="Detalle de Factura">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Detalle de Factura</h2>
            <p class="text-muted mb-0">Información completa de la factura</p>
        </div>

        <a href="{{ route('facturas.index') }}"
           class="btn btn-outline-secondary rounded-3">
            <i class="fa-solid fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    {{-- ================= CABECERA ================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>ID Factura</strong>
                    <div class="form-control bg-light">
                        {{ trim($factura->id_factura) }}
                    </div>
                </div>

                <div class="col-md-4">
                    <strong>Descripción</strong>
                    <div class="form-control bg-light">
                        {{ $factura->fac_descripcion ?? '—' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <strong>Fecha</strong>
                    <div class="form-control bg-light">
                        {{ $factura->fac_fecha_hora
                            ? \Carbon\Carbon::parse($factura->fac_fecha_hora)->format('Y-m-d H:i')
                            : '—' }}
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <strong>Estado</strong><br>
                <span class="badge
                    @if($factura->estado_fac === 'ABI')
                        bg-warning bg-opacity-25 text-warning
                    @elseif($factura->estado_fac === 'APR')
                        bg-success bg-opacity-25 text-success
                    @else
                        bg-danger bg-opacity-25 text-danger
                    @endif
                ">
                    {{ $factura->estado_fac }}
                </span>
            </div>

        </div>
    </div>

    {{-- ================= DETALLE ================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Detalle de Productos</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($detalles as $d)
                        <tr>
                            <td>{{ $d->pro_descripcion ?? $d->id_producto }}</td>
                            <td class="text-center">{{ $d->pxf_cantidad }}</td>
                            <td class="text-center">{{ number_format($d->pro_precio_venta, 2) }}</td>
                            <td class="text-center">
                                {{ number_format($d->pxf_cantidad * $d->pro_precio_venta, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No existen productos registrados en esta factura.
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- ================= ACCIONES ================= --}}
    <div class="d-flex gap-2">

        {{-- APROBAR (JEFE / ADMIN) --}}
        @if(
            auth()->user()->puede('VENTAS', 'APROBAR')
            && $factura->estado_fac === 'ABI'
        )
            <form method="POST" action="{{ route('facturas.aprobar', $factura) }}">
                @csrf
                <button class="btn btn-success"
                        onclick="return confirm('¿Desea aprobar esta factura?')">
                    <i class="fa-solid fa-check"></i>
                    Aprobar factura
                </button>
            </form>
        @endif

        {{-- ANULAR (JEFE / ADMIN) --}}
        @if(
            auth()->user()->puede('VENTAS', 'ANULAR')
            && $factura->estado_fac !== 'ANU'
        )
            <form method="POST" action="{{ route('facturas.destroy', $factura) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger"
                        onclick="return confirm('¿Desea anular esta factura?')">
                    <i class="fa-solid fa-ban"></i>
                    Anular factura
                </button>
            </form>
        @endif

    </div>

</x-adminlayout>
