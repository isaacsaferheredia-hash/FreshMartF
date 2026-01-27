<x-adminlayout title="Editar Recepción">

    <h2 class="fw-bold mb-4">Editar Recepción</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif




    <form method="POST"
          action="{{ route('recepciones.update', $recepcion->id_recibo) }}">
        @csrf
        @method('PUT')

        <table class="table table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Cantidad Recibida</th>
            </tr>
            </thead>
            <tbody>

            @foreach($detalles as $i => $d)
                <tr>
                    <td>{{ $d->producto->pro_descripcion }}</td>

                    <td class="text-center">
                        {{ $d->pxr_cantidad }}
                        <input type="hidden"
                               name="items[{{ $i }}][cantidad_original]"
                               value="{{ $d->pxr_cantidad }}">
                    </td>

                    <td class="text-center">
                        <input type="number"
                               name="items[{{ $i }}][qty_recibida]"
                               class="form-control text-center"
                               min="0"
                               max="{{ $d->pxr_cantidad }}"
                               value="{{ $d->pxr_qty_recibida }}">
                    </td>

                    <input type="hidden"
                           name="items[{{ $i }}][id_producto]"
                           value="{{ $d->id_producto }}">
                </tr>
            @endforeach

            </tbody>
        </table>

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success">
                <i class="fa-solid fa-check me-1"></i>
                Aprobar Recepción
            </button>

            <a href="{{ route('recepciones.index') }}"
               class="btn btn-outline-secondary">
                Cancelar
            </a>
        </div>

    </form>

</x-adminlayout>
