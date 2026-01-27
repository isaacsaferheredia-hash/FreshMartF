<x-adminlayout title="Permisos de Usuario">

    <h2 class="fw-bold mb-3">
        Permisos de {{ $user->name }}
    </h2>

    <form method="POST" action="{{ route('usuarios.permisos.update', $user) }}">
        @csrf

        <div class="card shadow-sm border-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                <tr>
                    <th>Área / Módulo</th>
                    <th>Rol Asignado</th>
                </tr>
                </thead>
                <tbody>

                @foreach($areas as $area)
                    <tr>
                        <td class="fw-semibold">
                            {{ $area->nombre }}
                        </td>

                        <td>
                            <select name="permisos[{{ $area->id_area }}]"
                                    class="form-select">

                                <option value="">— Sin acceso —</option>

                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id_rol }}"
                                            @if(
                                                isset($permisosActuales[$area->id_area]) &&
                                                $permisosActuales[$area->id_area]->id_rol === $rol->id_rol
                                            )
                                                selected
                                        @endif
                                    >
                                        {{ $rol->nombre }}
                                    </option>
                                @endforeach

                            </select>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <button class="btn btn-success">
                Guardar Permisos
            </button>

            <a href="{{ route('usuarios.index') }}"
               class="btn btn-secondary ms-2">
                Cancelar
            </a>
        </div>
    </form>

</x-adminlayout>
