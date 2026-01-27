<x-adminlayout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Productos</h2>
            <p class="text-muted mb-0">Gestión interna de productos</p>
        </div>

        {{-- BUSCADOR --}}
        @if(auth()->user()->puede('INVENTARIO', 'VISUALIZAR'))
            <form method="GET" action="{{ route('productos.index') }}" class="mb-3">
                <div class="input-group" style="max-width: 400px;">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           class="form-control"
                           placeholder="Buscar por ID, descripción o tipo">

                    <button class="btn btn-outline-secondary">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        @endif

        {{-- CREAR PRODUCTO --}}
        @if(auth()->user()->puede('INVENTARIO', 'CREAR'))
            <a href="{{ route('productos.create') }}"
               class="btn btn-success rounded-3">
                <i class="fa-solid fa-plus me-2"></i> Nuevo
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

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th class="text-end">Precio Venta</th>
                    <th class="text-end">Saldo Final</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>

                <tbody>
                @foreach($productos as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->id_producto }}</td>
                        <td>{{ $p->pro_descripcion }}</td>
                        <td>{{ $p->id_tipo }}</td>

                        <td class="text-end">{{ number_format($p->pro_precio_venta ?? 0, 2) }}</td>
                        <td class="text-end">{{ $p->pro_saldo_final ?? 0 }}</td>

                        <td>
                            <span class="badge
                                @if($p->estado_prod === 'ACT')
                                    bg-success bg-opacity-25 text-success
                                @else
                                    bg-danger bg-opacity-25 text-danger
                                @endif
                            ">
                                {{ $p->estado_prod }}
                            </span>
                        </td>

                        <td class="text-end">

                            {{-- VER --}}
                            @if(auth()->user()->puede('INVENTARIO', 'VISUALIZAR'))
                                <a href="{{ route('productos.show', $p->id_producto) }}"
                                   class="btn btn-sm btn-outline-info"
                                   title="Ver producto">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            @endif

                            {{-- EDITAR --}}
                            @if(auth()->user()->puede('INVENTARIO', 'CREAR'))
                                <a href="{{ route('productos.edit', $p->id_producto) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Editar producto">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            @endif

                            {{-- ANULAR / DESACTIVAR --}}
                            @if(
                                auth()->user()->puede('INVENTARIO', 'ANULAR')
                                && $p->estado_prod === 'ACT'
                            )
                                <form method="POST"
                                      action="{{ route('productos.destroy', $p->id_producto) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Desactivar producto"
                                            onclick="return confirm('¿Desactivar producto?')">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $productos->links('pagination::bootstrap-5') }}
    </div>

</x-adminlayout>
