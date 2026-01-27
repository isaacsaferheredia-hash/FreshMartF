<x-adminlayout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Facturas</h2>
            <p class="text-muted mb-0">Catálogo de facturas</p>
        </div>

        {{-- BUSCADOR --}}
        @if(auth()->user()->puede('VENTAS', 'VISUALIZAR'))
            <form method="GET" action="{{ route('facturas.index') }}" class="d-flex gap-2">
                <div class="input-group" style="max-width: 360px;">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           class="form-control"
                           placeholder="Buscar por ID factura o cliente">
                    <button class="btn btn-outline-secondary">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        @endif

        {{-- CREAR FACTURA --}}
        @if(auth()->user()->puede('VENTAS', 'CREAR'))
            <a href="{{ route('facturas.create') }}" class="btn btn-success rounded-3">
                <i class="fa-solid fa-plus me-2"></i> Registrar Factura
            </a>
        @endif
    </div>

    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning shadow-sm border-0">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('warning') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end">IVA</th>
                    <th class="text-end">Total</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>

                @foreach($facturas as $f)
                    @php
                        $total = ($f->fac_subtotal ?? 0) + ($f->fac_iva ?? 0);
                    @endphp

                    <tr>
                        <td class="fw-semibold">{{ $f->id_factura }}</td>
                        <td>{{ $f->cliente->cli_nombre ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($f->fac_fecha_hora)->format('Y-m-d H:i') }}</td>
                        <td class="text-end">{{ number_format($f->fac_subtotal ?? 0, 3) }}</td>
                        <td class="text-end">{{ number_format($f->fac_iva ?? 0, 3) }}</td>
                        <td class="text-end">{{ number_format($total, 3) }}</td>

                        <td>
                            <span class="badge
                                @if($f->estado_fac === 'ABI')
                                    bg-warning bg-opacity-25 text-warning
                                @elseif($f->estado_fac === 'APR')
                                    bg-success bg-opacity-25 text-success
                                @else
                                    bg-danger bg-opacity-25 text-danger
                                @endif
                            ">
                                {{ $f->estado_fac }}
                            </span>
                        </td>

                        <td class="text-end">

                            {{-- VER --}}
                            @if(auth()->user()->puede('VENTAS', 'VISUALIZAR'))
                                <a href="{{ route('facturas.show', $f->id_factura) }}"
                                   class="btn btn-sm btn-outline-info"
                                   title="Ver factura">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            @endif

                            {{-- EDITAR (solo si ABI) --}}
                            @if(
                                auth()->user()->puede('VENTAS', 'CREAR')
                                && $f->estado_fac === 'ABI'
                            )
                                <a href="{{ route('facturas.edit', $f->id_factura) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Editar factura">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            @endif

                            {{-- ANULAR --}}
                            @if(
                                auth()->user()->puede('VENTAS', 'ANULAR')
                                && $f->estado_fac !== 'ANU'
                            )
                                <form method="POST"
                                      action="{{ route('facturas.destroy', $f->id_factura) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Desea anular esta factura?')"
                                            title="Anular factura">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @endforeach

                @if($facturas->count() === 0)
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Sin datos para mostrar.
                        </td>
                    </tr>
                @endif

                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $facturas->links('pagination::bootstrap-5') }}
    </div>

</x-adminlayout>
