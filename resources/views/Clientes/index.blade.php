<x-adminlayout>

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="fa-solid fa-check me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Clientes</h2>
            <p class="text-muted mb-0">Gestión interna de clientes</p>
        </div>

        <form method="GET" action="{{ route('clientes.index') }}" class="mb-0">
            <div class="input-group" style="max-width: 400px;">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       class="form-control"
                       placeholder="Buscar por ID, cédula o nombre">
                <button class="btn btn-outline-secondary">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>

        {{-- CREAR CLIENTE --}}
        @if(auth()->user()->puede('VENTAS', 'CREAR'))
            <a href="{{ route('clientes.create') }}"
               class="btn btn-success rounded-3">
                <i class="fa-solid fa-user-plus me-2"></i> Nuevo
            </a>
        @endif
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>RUC / Cédula</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>

                @forelse($clientes as $c)
                    <tr>
                        <td class="fw-semibold">{{ $c->id_cliente }}</td>
                        <td>{{ $c->cli_nombre }}</td>
                        <td>{{ $c->cli_ruc_ced }}</td>
                        <td>
                            <span class="badge
                                @if($c->estado_cli === 'ACT')
                                    bg-success bg-opacity-25 text-success
                                @else
                                    bg-danger bg-opacity-25 text-danger
                                @endif
                            ">
                                {{ $c->estado_cli }}
                            </span>
                        </td>

                        <td class="text-end">

                            {{-- VER CLIENTE --}}
                            @if(auth()->user()->puede('VENTAS', 'VISUALIZAR'))
                                <a href="{{ route('clientes.show', $c->id_cliente) }}"
                                   class="btn btn-outline-info btn-sm"
                                   title="Ver cliente">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            @endif

                            {{-- EDITAR CLIENTE --}}
                            @if(auth()->user()->puede('VENTAS', 'CREAR'))
                                <a href="{{ route('clientes.edit', $c) }}"
                                   class="btn btn-outline-primary btn-sm"
                                   title="Editar cliente">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            @endif

                            {{-- ANULAR / DESACTIVAR CLIENTE --}}
                            @if(auth()->user()->puede('VENTAS', 'ANULAR'))
                                <form method="POST"
                                      action="{{ route('clientes.destroy', $c) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm"
                                            @if($c->estado_cli === 'ACT')
                                                onclick="return confirm('¿Desactivar cliente?')"
                                            @endif
                                            title="Desactivar cliente">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay clientes registrados
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $clientes->links('pagination::bootstrap-5') }}
    </div>

</x-adminlayout>
