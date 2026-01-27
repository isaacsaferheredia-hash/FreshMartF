<x-adminlayout title="Proveedores">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Proveedores</h2>
                <p class="text-muted mb-0 small">Gestión interna de proveedores de FreshMart</p>
            </div>

            <div class="d-flex gap-2 align-items-center">

                {{-- BUSCADOR --}}
                @if(auth()->user()->puede('COMPRAS', 'VISUALIZAR'))
                    <form method="GET" action="{{ route('proveedores.index') }}" class="d-flex">
                        <div class="input-group">
                            <input type="text" name="q" value="{{ request('q') }}"
                                   class="form-control form-control-sm border-end-0"
                                   placeholder="Buscar por ID, RUC o nombre" style="width: 250px;">
                            <button class="btn btn-sm btn-outline-secondary border-start-0" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                @endif

                {{-- CREAR PROVEEDOR --}}
                @if(auth()->user()->puede('COMPRAS', 'CREAR'))
                    <a href="{{ route('proveedores.create') }}"
                       class="btn btn-success btn-sm d-flex align-items-center gap-2">
                        <i class="fa-solid fa-user-plus"></i> Nuevo Proveedor
                    </a>
                @endif

            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 overflow-hidden" style="border-radius: 8px;">
                    <thead class="table-light text-muted small uppercase">
                    <tr>
                        <th class="ps-4" style="width: 120px;">ID</th>
                        <th>Nombre Comercial</th>
                        <th>RUC / Cédula</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-4" style="width: 150px;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="small">

                    @forelse ($proveedores as $proveedor)
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">
                                {{ $proveedor->id_proveedor }}
                            </td>

                            <td class="fw-medium text-secondary">
                                {{ $proveedor->prv_nombre }}
                            </td>

                            <td class="text-muted">
                                {{ $proveedor->prv_ruc_ced }}
                            </td>

                            <td class="text-center">
                                @if($proveedor->estado_prv === 'ACT')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill" style="font-size: 0.7rem;">
                                            ACT
                                        </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill" style="font-size: 0.7rem;">
                                            INA
                                        </span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">

                                    {{-- VER --}}
                                    @if(auth()->user()->puede('COMPRAS', 'VISUALIZAR'))
                                        <a href="{{ route('proveedores.show', $proveedor) }}"
                                           class="btn btn-sm btn-outline-info p-1 px-2"
                                           title="Ver">
                                            <i class="fa-solid fa-magnifying-glass small"></i>
                                        </a>
                                    @endif

                                    {{-- EDITAR --}}
                                    @if(
                                        auth()->user()->puede('COMPRAS', 'CREAR')
                                        && $proveedor->estado_prv === 'ACT'
                                    )
                                        <a href="{{ route('proveedores.edit', $proveedor) }}"
                                           class="btn btn-sm btn-outline-primary p-1 px-2"
                                           title="Editar">
                                            <i class="fa-solid fa-pen small"></i>
                                        </a>
                                    @endif

                                    {{-- DESACTIVAR --}}
                                    @if(
                                        auth()->user()->puede('COMPRAS', 'ANULAR')
                                        && $proveedor->estado_prv === 'ACT'
                                    )
                                        <form action="{{ route('proveedores.destroy', $proveedor) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger p-1 px-2"
                                                    onclick="return confirm('¿Desactivar proveedor?')"
                                                    title="Desactivar">
                                                <i class="fa-solid fa-ban small"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- INACTIVO --}}
                                    @if($proveedor->estado_prv !== 'ACT')
                                        <button class="btn btn-sm btn-outline-secondary p-1 px-2" disabled>
                                            <i class="fa-solid fa-circle-check small"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No se encontraron resultados
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 small text-muted">
            <div>
                Showing {{ $proveedores->firstItem() }} to {{ $proveedores->lastItem() }} of {{ $proveedores->total() }} results
            </div>
            <div>
                {{ $proveedores->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
</x-adminlayout>
