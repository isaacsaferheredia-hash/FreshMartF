<x-adminlayout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Órdenes de Compra</h2>
            <p class="text-muted mb-0">Catálogo de órdenes de compra</p>
        </div>

        {{-- BUSCADOR (VER) --}}
        @if(auth()->user()->puede('COMPRAS', 'VER'))
            <form method="GET" action="{{ route('compras.index') }}" class="d-flex gap-2">
                <div class="input-group" style="max-width: 360px;">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           class="form-control"
                           placeholder="Buscar por ID compra o proveedor">
                    <button class="btn btn-outline-secondary">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        @endif

        {{-- CREAR ORDEN --}}
        @if(auth()->user()->puede('COMPRAS', 'CREAR'))
            <a href="{{ route('compras.create') }}" class="btn btn-success rounded-3">
                <i class="fa-solid fa-plus me-2"></i> Registrar Orden
            </a>
        @endif
    </div>

    {{-- MENSAJES --}}
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-3">
            <i class="fa-solid fa-circle-check me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($mensajeVacio)
        <div class="alert alert-info shadow-sm border-0">
            {{ $mensajeVacio }}
        </div>
    @endif

    @if($mensajeSinResultados)
        <div class="alert alert-warning shadow-sm border-0">
            {{ $mensajeSinResultados }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end">IVA</th>
                    <th class="text-end">Total</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>

                <tbody>
                @foreach($compras as $c)
                    @php
                        $total = ($c->oc_subtotal ?? 0) + ($c->oc_iva ?? 0);
                    @endphp

                    <tr>
                        <td class="fw-semibold">{{ $c->id_compra }}</td>
                        <td>{{ $c->id_proveedor }}</td>
                        <td>{{ \Carbon\Carbon::parse($c->oc_fecha_hora)->format('Y-m-d H:i') }}</td>
                        <td class="text-end">{{ number_format($c->oc_subtotal ?? 0, 3) }}</td>
                        <td class="text-end">{{ number_format($c->oc_iva ?? 0, 3) }}</td>
                        <td class="text-end">{{ number_format($total, 3) }}</td>

                        <td>
                            <span class="badge
                                @if($c->estado_oc === 'APR')
                                    bg-success bg-opacity-25 text-success
                                @else
                                    bg-danger bg-opacity-25 text-danger
                                @endif
                            ">
                                {{ $c->estado_oc }}
                            </span>
                        </td>

                        <td class="text-end">

                            {{-- VER --}}
                            @if(auth()->user()->puede('COMPRAS', 'VER'))
                                <a href="{{ route('compras.show', $c->id_compra) }}"
                                   class="btn btn-sm btn-outline-info"
                                   title="Ver orden">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            @endif

                            {{-- EDITAR (solo AUXILIAR / GERENTE / ADMIN y si NO está aprobada) --}}
                            @if(auth()->user()->puede('COMPRAS', 'EDITAR') && $c->estado_oc !== 'APR')
                                <a href="{{ route('compras.edit', $c->id_compra) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Editar orden">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            @endif

                            {{-- ANULAR (JEFE / GERENTE / ADMIN) --}}
                            @if(auth()->user()->puede('COMPRAS', 'ANULAR') && $c->estado_oc !== 'ANU')
                                <form method="POST"
                                      action="{{ route('compras.destroy', $c->id_compra) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Anular orden"
                                            onclick="return confirm('¿Desea anular esta orden de compra?')">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @endforeach

                @if($compras->count() === 0 && !$mensajeVacio && !$mensajeSinResultados)
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
        {{ $compras->links('pagination::bootstrap-5') }}
    </div>

</x-adminlayout>
