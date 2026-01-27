<x-adminlayout>

    <h2 class="fw-bold mb-4">Registrar Factura</h2>

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

    <form action="{{ route('facturas.store') }}" method="POST" novalidate>
        @csrf

        {{-- CLIENTE --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Cliente</label>
            <select name="cliente_id"
                    class="form-select @error('cliente_id') is-invalid @enderror"
                    required>
                <option value="">Seleccione un cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id_cliente }}"
                        {{ old('cliente_id') == $cliente->id_cliente ? 'selected' : '' }}>
                        {{ $cliente->cli_nombre }}
                    </option>
                @endforeach
            </select>
            @error('cliente_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- FECHA --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Fecha</label>
            <input type="date"
                   name="fecha"
                   class="form-control bg-light"
                   value="{{ now()->toDateString() }}"
                   readonly>
        </div>

        <hr>

        {{-- DETALLE --}}
        <h5 class="mt-4 mb-2 fw-bold">Detalle de Productos</h5>

        {{-- Error de productos --}}
        @error('productos')
        <div class="text-danger small mb-2">
            {{ $message }}
        </div>
        @enderror

        <div id="productos-container"></div>

        <button type="button"
                class="btn btn-outline-primary mt-3"
                id="btnAgregarProducto">
            <i class="fa-solid fa-plus me-1"></i> Añadir producto
        </button>

        <hr class="my-4">

        {{-- TOTALES --}}
        <div class="row justify-content-end">
            <div class="col-md-4">
                <div class="mb-2">
                    <label class="form-label">Subtotal</label>
                    <input id="subtotalFactura" class="form-control text-end" readonly value="0.00">
                </div>
                <div class="mb-2">
                    <label class="form-label">IVA (15%)</label>
                    <input id="ivaFactura" class="form-control text-end" readonly value="0.00">
                </div>
                <div>
                    <label class="form-label fw-bold">Total</label>
                    <input id="totalFactura" class="form-control text-end fw-bold" readonly value="0.00">
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-success">
                <i class="fa-solid fa-floppy-disk me-1"></i>
                Guardar Factura
            </button>
            <a href="{{ route('facturas.index') }}" class="btn btn-outline-secondary">
                Cancelar
            </a>
        </div>
    </form>

</x-adminlayout>

{{-- ========================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================= --}}
<script>
    const container = document.getElementById('productos-container');

    document.getElementById('btnAgregarProducto').addEventListener('click', () => {

        const fila = document.createElement('div');
        fila.className = 'row mb-3 align-items-end producto-row';

        fila.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Producto</label>
                <select class="form-select producto">
                    <option value="">Seleccione</option>
                    @foreach($productos as $producto)
        <option value="{{ $producto->id_producto }}"
                                data-precio="{{ $producto->pro_precio_venta }}">
                            {{ $producto->pro_descripcion }}
        </option>
@endforeach
        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label">Cantidad</label>
        <input type="number"
               class="form-control cantidad"
               value="1"
               min="1">
    </div>

    <div class="col-md-2">
        <label class="form-label">Precio</label>
        <input type="text"
               class="form-control precio text-end"
               readonly>
    </div>

    <div class="col-md-2">
        <label class="form-label">Subtotal</label>
        <input type="text"
               class="form-control subtotal text-end"
               readonly value="0.00">
    </div>

    <div class="col-md-2 text-end">
        <button type="button"
                class="btn btn-outline-danger btn-sm btnEliminar">
            <i class="fa-solid fa-trash"></i>
        </button>
    </div>
`;

        container.appendChild(fila);
        actualizarSelects();
    });

    document.addEventListener('change', manejarCambios);
    document.addEventListener('input', manejarCambios);

    function manejarCambios(e) {
        const fila = e.target.closest('.producto-row');
        if (!fila) return;

        const select   = fila.querySelector('.producto');
        const cantidad = fila.querySelector('.cantidad');
        const precioEl = fila.querySelector('.precio');
        const subEl    = fila.querySelector('.subtotal');

        const option = select.options[select.selectedIndex];
        const precio = parseFloat(option?.dataset.precio) || 0;

        precioEl.value = precio.toFixed(2);
        subEl.value    = (precio * (cantidad.value || 0)).toFixed(2);

        // 🔑 usar ID del producto como clave
        if (select.value) {
            cantidad.name = `productos[${select.value}][cantidad]`;
        }

        calcularTotales();
        actualizarSelects();
    }

    document.addEventListener('click', e => {
        if (e.target.closest('.btnEliminar')) {
            e.target.closest('.producto-row').remove();
            calcularTotales();
            actualizarSelects();
        }
    });

    function calcularTotales() {
        let subtotal = 0;

        document.querySelectorAll('.subtotal').forEach(el => {
            subtotal += parseFloat(el.value) || 0;
        });

        const iva = subtotal * 0.15;

        document.getElementById('subtotalFactura').value = subtotal.toFixed(2);
        document.getElementById('ivaFactura').value      = iva.toFixed(2);
        document.getElementById('totalFactura').value    = (subtotal + iva).toFixed(2);
    }

    function actualizarSelects() {
        const seleccionados = Array.from(document.querySelectorAll('.producto'))
            .map(sel => sel.value)
            .filter(v => v);

        document.querySelectorAll('.producto').forEach(select => {
            Array.from(select.options).forEach(option => {
                if (!option.value) return;
                option.disabled =
                    seleccionados.includes(option.value) &&
                    option.value !== select.value;
            });
        });
    }
</script>
