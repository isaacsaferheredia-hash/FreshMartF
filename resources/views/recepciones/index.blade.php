<x-adminlayout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Recepciones</h2>
            <p class="text-muted mb-0">Listado de recepciones</p>
        </div>

        {{-- BUSCADOR --}}
        @if(auth()->user()->puede('INVENTARIO', 'VISUALIZAR'))
            <form method="GET" action="{{ route('recepciones.index') }}" class="d-flex gap-2">
                <div class="input-group" style="max-width: 360px;">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           class="form-control"
                           placeholder="Buscar por ID o descripción">
                    <button class="btn btn-outline-secondary">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>

                @foreach($recepciones as $r)
                    <tr>
                        <td class="fw-semibold">{{ $r->id_recibo }}</td>
                        <td>{{ $r->rec_descripcion }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->rec_fechahora)->format('Y-m-d H:i') }}</td>
                        <td>
                            <span class="badge
                                {{ $r->estado_rec === 'ABI'
                                    ? 'bg-success bg-opacity-25 text-success'
                                    : 'bg-danger bg-opacity-25 text-danger' }}">
                                {{ $r->estado_rec }}
                            </span>
                        </td>

                        <td class="text-end">

                            {{-- VER --}}
                            @if(auth()->user()->puede('INVENTARIO', 'VISUALIZAR'))
                                <a href="{{ route('recepciones.show', $r->id_recibo) }}"
                                   class="btn btn-sm btn-outline-info"
                                   title="Ver recepción">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </a>
                            @endif

                            {{-- EDITAR (AUXILIAR / ADMIN y solo si ABI) --}}
                            @if(
                                auth()->user()->puede('INVENTARIO', 'CREAR')
                                && $r->estado_rec === 'ABI'
                            )
                                <a href="{{ route('recepciones.edit', $r->id_recibo) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            @endif

                            {{-- ANULAR (JEFE / ADMIN) --}}
                            @if(
                                auth()->user()->puede('INVENTARIO', 'ANULAR')
                                && $r->estado_rec !== 'ANU'
                            )
                                <form method="POST"
                                      action="{{ route('recepciones.destroy', $r->id_recibo) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Anular esta recepción?')">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @endforeach

                @if($recepciones->count() === 0)
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Sin datos para mostrar.
                        </td>
                    </tr>
                @endif

                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $recepciones->links('pagination::bootstrap-5') }}
    </div>

</x-adminlayout>
