<x-adminlayout title="Usuarios">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Usuarios</h2>

        <a href="{{ route('usuarios.create') }}" class="btn btn-success">
            <i class="fa-solid fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>

    {{-- 🔍 FILTROS --}}
    <form method="GET" action="{{ route('usuarios.index') }}" class="row g-2 mb-4">

        {{-- FILTRO ROL --}}
        <div class="col-md-3">
            <select name="rol" class="form-select">
                <option value="">Todos los roles</option>
                @foreach(['ADMIN','JEFE','AUXILIAR','OPERATIVO','CLIENTE'] as $rol)
                    <option value="{{ $rol }}" @selected(request('rol') === $rol)>
                        {{ $rol }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- FILTRO ESTADO --}}
        <div class="col-md-3">
            <select name="estado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="ACTIVO" @selected(request('estado') === 'ACTIVO')>
                    Activo
                </option>
                <option value="INACTIVO" @selected(request('estado') === 'INACTIVO')>
                    Inactivo
                </option>
            </select>
        </div>

        {{-- BOTÓN FILTRAR --}}
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100">
                <i class="fa-solid fa-filter"></i> Filtrar
            </button>
        </div>

        {{-- LIMPIAR --}}
        @if(request()->hasAny(['rol','estado']))
            <div class="col-md-2">
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-danger w-100">
                    Limpiar
                </a>
            </div>
        @endif

    </form>

    <div class="card shadow-sm border-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol Global</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
            </tr>
            </thead>

            <tbody>
            @forelse($usuarios as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>

                    {{-- ROL --}}
                    <td>
                        <span class="badge bg-secondary">
                            {{ $u->rol }}
                        </span>
                    </td>

                    {{-- ESTADO --}}
                    <td>
                        @if($u->deleted_at)
                            <span class="badge bg-danger">Inactivo</span>
                        @else
                            <span class="badge bg-success">Activo</span>
                        @endif
                    </td>

                    {{-- ACCIONES --}}
                    <td class="text-end">

                        <a href="{{ route('usuarios.permisos.edit', $u) }}"
                           class="btn btn-sm btn-outline-primary"
                           title="Gestionar permisos">
                            <i class="fa-solid fa-shield-halved"></i>
                        </a>

                        {{-- SOFT DELETE --}}
                        @if(!$u->deleted_at)
                            <form action="{{ route('usuarios.destroy', $u) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Desactivar este usuario?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-user-slash"></i>
                                </button>
                            </form>
                        @endif

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No hay usuarios registrados
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $usuarios->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

</x-adminlayout>
