<x-adminlayout title="Detalle del Proveedor">

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-user"></i> Información del Proveedor
                </h5>
            </div>

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ID Proveedor:</strong>
                        <div class="form-control bg-light">
                            {{ $proveedor->id_proveedor }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <strong>Nombre:</strong>
                        <div class="form-control bg-light">
                            {{ $proveedor->prv_nombre }}
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>RUC / Cédula:</strong>
                        <div class="form-control bg-light">
                            {{ $proveedor->prv_ruc_ced }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <strong>Email:</strong>
                        <div class="form-control bg-light">
                            {{ $proveedor->prv_mail }}
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Teléfono:</strong>
                        <div class="form-control bg-light">
                            {{ $proveedor->prv_telefono ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <strong>Celular:</strong>
                        <div class="form-control bg-light">
                            {{ $proveedor->prv_celular ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Dirección:</strong>
                    <div class="form-control bg-light">
                        {{ $proveedor->prv_direccion }}
                    </div>
                </div>

                <div class="mb-4">
                    <strong>Estado:</strong>
                    <span class="badge {{ $proveedor->estado_prv === 'ACT' ? 'bg-success' : 'bg-danger' }}">
                        {{ $proveedor->estado_prv }}
                    </span>
                </div>

                {{-- ACCIONES --}}
                <div class="mt-3 d-flex gap-2">

                    {{-- VOLVER --}}
                    <a href="{{ route('proveedores.index') }}"
                       class="btn btn-secondary text-white"
                       style="background-color: #6c757d;">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>

                    {{-- EDITAR (AUXILIAR / ADMIN) --}}
                    @if(
                        auth()->user()->puede('COMPRAS', 'CREAR')
                        && $proveedor->estado_prv === 'ACT'
                    )
                        <a href="{{ route('proveedores.edit', $proveedor->id_proveedor) }}"
                           class="btn btn-warning"
                           style="background-color: #ffc107;">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                    @endif

                </div>

            </div>
        </div>
    </div>

</x-adminlayout>
