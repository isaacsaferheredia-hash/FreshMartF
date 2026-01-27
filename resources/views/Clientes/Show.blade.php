<x-adminlayout title="Detalle del Cliente">

    <div class="container mt-4">

        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">
                    <i class="fa-solid fa-user"></i> Información del Cliente
                </h5>
            </div>

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ID Cliente:</strong>
                        <div class="form-control bg-light">{{ $cliente->id_cliente }}</div>
                    </div>

                    <div class="col-md-6">
                        <strong>Nombre:</strong>
                        <div class="form-control bg-light">{{ $cliente->cli_nombre }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>RUC / Cédula:</strong>
                        <div class="form-control bg-light">{{ $cliente->cli_ruc_ced }}</div>
                    </div>

                    <div class="col-md-6">
                        <strong>Email:</strong>
                        <div class="form-control bg-light">{{ $cliente->cli_mail }}</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Teléfono:</strong>
                        <div class="form-control bg-light">
                            {{ $cliente->cli_telefono ?? 'No registrado' }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <strong>Celular:</strong>
                        <div class="form-control bg-light">
                            {{ $cliente->cli_celular ?? 'No registrado' }}
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <strong>Dirección:</strong>
                    <div class="form-control bg-light">{{ $cliente->cli_direccion }}</div>
                </div>

                <div class="mb-4">
                    <strong>Estado:</strong>
                    <span class="badge
                        {{ $cliente->estado_cli === 'ACT'
                            ? 'bg-success'
                            : 'bg-danger' }}">
                        {{ $cliente->estado_cli }}
                    </span>
                </div>

                <div class="mt-3">
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>

                    @if($cliente->estado_cli === 'ACT')
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                    @endif
                </div>

            </div>
        </div>

    </div>

</x-adminlayout>
