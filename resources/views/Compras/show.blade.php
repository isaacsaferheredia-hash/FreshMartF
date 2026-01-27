<x-adminlayout title="Detalle de Orden de Compra">

    <div class="container mt-4">

        {{-- INFORMACIÓN GENERAL --}}
        <div class="card shadow mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Información de la Orden
                </h5>
            </div>

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>ID Orden:</strong>
                        <div class="form-control bg-light">
                            {{ $compra->id_compra }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <strong>Proveedor:</strong>
                        <div class="form-control bg-light">
                            {{ $compra->id_proveedor }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <strong>Fecha:</strong>
                        <div class="form-control bg-light">
                            {{ \Carbon\Carbon::parse($compra->oc_fecha_hora)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Subtotal:</strong>
                        <div class="form-control bg-light">
                            $ {{ number_format($compra->oc_subtotal ?? 0, 2) }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <strong>IVA:</strong>
                        <div class="form-control bg-light">
                            $ {{ number_format($compra->oc_iva ?? 0, 2) }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <strong>Total:</strong>
                        <div class="form-control bg-light">
                            $ {{ number_format(($compra->oc_subtotal ?? 0) + ($compra->oc_iva ?? 0), 2) }}
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                    <strong>Estado:</strong><br>
                    <span class="badge {{ $compra->estado_oc === 'APR' ? 'bg-success' : 'bg-danger' }}">
                        {{ $compra->estado_oc }}
                    </span>
                </div>

            </div>
        </div>

        {{-- DETALLE --}}
        <div class="card shadow mb-4">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="fa-solid fa-list"></i> Detalle de la Orden
                </h6>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>ID Producto</th>
                        <th>Descripción</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Costo</th>
                        <th class="text-end">Subtotal</th>
                        <th>Estado</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($compra->detalles as $d)
                        @php
                            $cantidad = (float)($d->pxo_cantidad ?? 0);
                            $costo    = (float)($d->pxo_valor ?? 0);
                            $sub      = $cantidad * $costo;
                            $desc     = $productos[$d->id_producto]->pro_descripcion ?? 'N/A';
                        @endphp

                        <tr>
                            <td>{{ $d->id_producto }}</td>
                            <td>{{ $desc }}</td>
                            <td class="text-end">{{ $cantidad }}</td>
                            <td class="text-end">$ {{ number_format($costo, 3) }}</td>
                            <td class="text-end">$ {{ number_format($sub, 3) }}</td>
                            <td>
                                    <span class="badge {{ $d->estado_pxoc === 'ABI' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $d->estado_pxoc }}
                                    </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                No existen detalles registrados
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ACCIONES --}}
        <div class="mt-3 d-flex gap-2">

            {{-- VOLVER --}}
            <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>

            {{-- EDITAR (AUXILIAR / ADMIN y solo si NO está aprobada) --}}
            @if(
                auth()->user()->puede('COMPRAS', 'EDITAR')
                && $compra->estado_oc !== 'APR'
            )
                <a href="{{ route('compras.edit', $compra) }}" class="btn btn-warning">
                    <i class="fa-solid fa-pen"></i> Editar
                </a>
            @endif

            {{-- APROBAR (JEFE / ADMIN) --}}
            @if(
                auth()->user()->puede('COMPRAS', 'APROBAR')
                && $compra->estado_oc === 'ABI'
            )
                <form method="POST"
                      action="{{ route('compras.aprobar', $compra->id_compra) }}">
                    @csrf
                    <button class="btn btn-success"
                            onclick="return confirm('¿Desea aprobar esta orden de compra?')">
                        <i class="fa-solid fa-check"></i> Aprobar
                    </button>
                </form>
            @endif

            {{-- ANULAR (JEFE / ADMIN) --}}
            @if(
                auth()->user()->puede('COMPRAS', 'ANULAR')
                && $compra->estado_oc !== 'ANU'
            )
                <form method="POST"
                      action="{{ route('compras.destroy', $compra->id_compra) }}">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger"
                            onclick="return confirm('¿Desea anular esta orden de compra?')">
                        <i class="fa-solid fa-ban"></i> Anular
                    </button>
                </form>
            @endif

        </div>

    </div>

</x-adminlayout>
