<x-adminlayout>

    <h2 class="fw-bold mb-4">Editar Factura</h2>

    {{-- 🔴 Error crítico --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🟡 Faltan datos obligatorios --}}
    @if($errors->any())
        <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            Faltan datos obligatorios.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST"
          action="{{ route('facturas.update', trim($factura->id_factura)) }}"
          novalidate>
        @csrf
        @method('PUT')

        <input type="hidden" name="fac_fecha_hora" value="{{ $factura->fac_fecha_hora }}">

        {{-- ================= CLIENTE ================= --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Cliente</label>
            <select name="id_cliente"
                    class="form-select @error('id_cliente') is-invalid @enderror"
                    required>
                <option value="">Seleccione un cliente</option>
                @foreach($clientes as $c)
                    <option value="{{ trim($c->id_cliente) }}"
                        {{ trim(old('id_cliente', $factura->id_cliente)) === trim($c->id_cliente) ? 'selected' : '' }}>
                        {{ $c->cli_nombre }}
                    </option>
                @endforeach
            </select>
            @error('id_cliente')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <hr>

        {{-- ================= DETALLE ================= --}}
        <h5 class="fw-bold mb-2">Detalle de Productos</h5>

        @error('items')
        <div class="text-danger small mb-2">
            {{ $message }}
        </div>
        @enderror

        <table class="table table-hover align-middle" id="tablaProductos">
            <thead class="table-light">
            <tr>
                <th>Producto</th>
                <th style="width:120px">Cantidad</th>
                <th style="width:120px">Precio</th>
                <th style="width:120px">Subtotal</th>
                <th style="width:60px"></th>
            </tr>
            </thead>
            <tbody>

            @foreach($detalles as $i => $d)
                <tr class="producto-row">
                    <td>
                        <select name="items[{{ $i }}][id_producto]"
                                class="form-select producto-select @error("items.$i.id_producto") is-invalid @enderror">
                            <option value="">Seleccione</option>
                            @foreach($productos as $p)
                                <option value="{{ trim($p->id_producto) }}"
                                        data-precio="{{ $p->pro_precio_venta }}"
                                    {{ trim($d->id_producto) === trim($p->id_producto) ? 'selected' : '' }}>
                                    {{ $p->pro_descripcion }}
                                </option>
                            @endforeach
                        </select>
                        @error("items.$i.id_producto")
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>

                    <td>
                        <input type="number"
                               name="items[{{ $i }}][cantidad]"
                               class="form-control cantidad @error("items.$i.cantidad") is-invalid @enderror"
                               value="{{ old("items.$i.cantidad", $d->pxf_cantidad) }}"
                               min="1">
                        @error("items.$i.cantidad")
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>

                    <td>
                        <input type="text"
                               class="form-control precio text-end"
                               value="{{ number_format($d->pxf_precio, 2) }}"
                               readonly>
                    </td>

                    <td class="subtotal text-end">
                        {{ number_format($d->pxf_subtotal, 2) }}
                    </td>

                    <td class="text-end">
                        <button type="button"
                                class="btn btn-outline-danger btn-sm btnEliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

        <button type="button"
                class="btn btn-outline-primary"
                id="btnAgregar">
            <i class="fa-solid fa-plus me-1"></i> Agregar producto
        </button>

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Guardar cambios
            </button>
            <a href="{{ route('facturas.index') }}"
               class="btn btn-outline-secondary">
                Cancelar
            </a>
        </div>

    </form>

</x-adminlayout>

{{-- ===================== --}}
{{-- JAVASCRIPT --}}
{{-- ===================== --}}
<script>
    let index = {{ $detalles->count() }};
    const tbody = document.querySelector('#tablaProductos tbody');

    document.getElementById('btnAgregar').addEventListener('click', () => {

        const row = document.createElement('tr');
        row.classList.add('producto-row');

        row.innerHTML = `
            <td>
                <select name="items[${index}][id_producto]"
                        class="form-select producto-select">
                    <option value="">Seleccione</option>
                    @foreach($productos as $p)
        <option value="{{ trim($p->id_producto) }}"
                                data-precio="{{ $p->pro_precio_venta }}">
                            {{ $p->pro_descripcion }}
        </option>
@endforeach
        </select>
    </td>

    <td>
        <input type="number"
               name="items[${index}][cantidad]"
                       class="form-control cantidad"
                       value="1"
                       min="1">
            </td>

            <td>
                <input type="text"
                       class="form-control precio text-end"
                       readonly>
            </td>

            <td class="subtotal text-end">0.00</td>

            <td class="text-end">
                <button type="button"
                        class="btn btn-outline-danger btn-sm btnEliminar">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);
        index++;
        actualizarSelects();
    });

    document.addEventListener('change', e => {
        if (e.target.classList.contains('producto-select') ||
            e.target.classList.contains('cantidad')) {

            const row = e.target.closest('.producto-row');
            const select = row.querySelector('.producto-select');
            const cantidad = row.querySelector('.cantidad').value || 0;

            const precio = parseFloat(
                select.options[select.selectedIndex]?.dataset.precio || 0
            );

            row.querySelector('.precio').value = precio.toFixed(2);
            row.querySelector('.subtotal').textContent =
                (precio * cantidad).toFixed(2);

            actualizarSelects();
        }
    });

    document.addEventListener('click', e => {
        if (e.target.closest('.btnEliminar')) {
            e.target.closest('.producto-row').remove();
            actualizarSelects();
        }
    });

    function actualizarSelects() {
        const usados = Array.from(document.querySelectorAll('.producto-select'))
            .map(sel => sel.value)
            .filter(v => v);

        document.querySelectorAll('.producto-select').forEach(select => {
            Array.from(select.options).forEach(opt => {
                if (!opt.value) return;
                opt.disabled = usados.includes(opt.value) && opt.value !== select.value;
            });
        });
    }

    document.addEventListener('DOMContentLoaded', actualizarSelects);
</script>
