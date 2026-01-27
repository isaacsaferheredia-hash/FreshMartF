<x-adminlayout title="Detalle de Recepción">

    {{-- ================= CABECERA ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Detalle de Recepción</h2>
            <p class="text-muted mb-0">Información completa de la recepción</p>
        </div>

        <a href="{{ route('recepciones.index') }}"
           class="btn btn-outline-secondary rounded-3">
            <i class="fa-solid fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    {{-- ================= CABECERA INFO ================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>ID Recepción</strong>
                    <div class="form-control bg-light">
                        {{ $recepcion->id_recibo }}
                    </div>
                </div>

                <div class="col-md-4">
                    <strong>Descripción</strong>
                    <div class="form-control bg-light">
                        {{ $recepcion->rec_descripcion ?? '—' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <strong>Fecha</strong>
                    <div class="form-control bg-light">
                        {{ \Carbon\Carbon::parse($recepcion->rec_fechahora)->format('Y-m-d H:i') }}
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <strong>Estado</strong><br>
                <span class="badge
                    @if($recepcion->estado_rec === 'ABI')
                        bg-warning bg-opacity-25 text-warning
                    @elseif($recepcion->estado_rec === 'APR')
                        bg-success bg-opacity-25 text-success
                    @else
                        bg-danger bg-opacity-25 text-danger
                    @endif
                ">
                    {{ $recepcion->estado_rec }}
                </span>
            </div>

        </div>
    </div>

    {{-- ================= DETALLE ================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h5 class="fw-bold mb-3">Detalle de Productos</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Cantidad Recibida</th>
                        <th class="text-center">Estado</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($detalles as $d)
                        <tr>
                            <td>{{ $d->pro_descripcion }}</td>

                            <td class="text-center">
                                {{ $d->pxr_cantidad }}
                            </td>

                            <td class="text-center">
                                {{ $d->pxr_qty_recibida }}
                            </td>

                            <td class="text-center">
                                <span class="badge
                                    @if($d->estado_pxr === 'ABI')
                                        bg-warning bg-opacity-25 text-warning
                                    @elseif($d->estado_pxr === 'APR')
                                        bg-success bg-opacity-25 text-success
                                    @else
                                        bg-danger bg-opacity-25 text-danger
                                    @endif
                                ">
                                    {{ $d->estado_pxr }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No existen productos registrados en esta recepción.
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</x-adminlayout>
