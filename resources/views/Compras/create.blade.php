<x-adminlayout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Registrar Orden de Compra</h2>
            <p class="text-muted mb-0">Crea una nueva orden de compra</p>
        </div>

        <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="fa-solid fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    {{-- 🔴 Error crítico --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🟡 Advertencia --}}
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🟡 Faltan datos obligatorios --}}
    @if($errors->any())
        <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 mb-3">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            Faltan datos obligatorios. Revise los campos marcados.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form id="formCompra"
                  method="POST"
                  action="{{ route('compras.store') }}"
                  novalidate>
                @csrf

                <div class="row g-3 align-items-end">
                    {{-- PROVEEDOR --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Proveedor</label>
                        <select name="id_proveedor"
                                class="form-select @error('id_proveedor') is-invalid @enderror"
                                required>
                            <option value="">-- Seleccione --</option>
                            @foreach($proveedores as $p)
                                <option value="{{ $p->id_proveedor }}"
                                    {{ old('id_proveedor') == $p->id_proveedor ? 'selected' : '' }}>
                                    {{ $p->id_proveedor }} - {{ $p->prv_nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_proveedor')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- FECHA --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha</label>
                        <input type="datetime-local"
                               class="form-control bg-light"
                               value="{{ now()->format('Y-m-d\TH:i') }}"
                               readonly
                               style="pointer-events:none;">
                        <input type="hidden"
                               name="oc_fecha_hora"
                               value="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>
                </div>

                <hr class="my-4">

                {{-- DETALLE --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Detalle de Productos</h5>
                    <button type="button"
                            id="btnAdd"
                            class="btn btn-outline-primary btn-sm rounded-3">
                        <i class="fa-solid fa-plus me-2"></i> Agregar producto
                    </button>
                </div>

                {{-- Error de items --}}
                @error('items')
                <div class="text-danger small mb-2">
                    {{ $message }}
                </div>
                @enderror

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="detalleTable">
                        <thead class="table-light">
                        <tr>
                            <th style="width:45%">Producto</th>
                            <th style="width:15%" class="text-center">Cantidad</th>
                            <th style="width:20%" class="text-end">Costo</th>
                            <th style="width:15%" class="text-end">Subtotal</th>
                            <th style="width:5%"></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                {{-- TOTALES --}}
                <div class="row mt-3">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="mb-2">
                                    <label class="form-label mb-1">Subtotal</label>
                                    <input type="text"
                                           id="txtSubtotal"
                                           class="form-control text-end"
                                           value="0.000"
                                           readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mb-1">IVA (12%)</label>
                                    <input type="text"
                                           id="txtIva"
                                           class="form-control text-end"
                                           value="0.000"
                                           readonly>
                                </div>
                                <div>
                                    <label class="form-label mb-1 fw-bold">Total</label>
                                    <input type="text"
                                           id="txtTotal"
                                           class="form-control text-end fw-bold"
                                           value="0.000"
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-success rounded-3">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Registrar
                    </button>
                    <a href="{{ route('compras.index') }}"
                       class="btn btn-outline-secondary rounded-3">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    @php
        $productosJS = $productos->map(function ($p) {
            return [
                'id'     => $p->id_producto,
                'nombre' => $p->pro_descripcion,
                'costo'  => (float)($p->pro_precio_venta ?? 0),
            ];
        })->values();
    @endphp

    {{-- ========================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================= --}}
    <script>
        const productos = @json($productosJS);
        const IVA_RATE = 0.12;

        const tbody = document.querySelector('#detalleTable tbody');
        const btnAdd = document.getElementById('btnAdd');

        const txtSubtotal = document.getElementById('txtSubtotal');
        const txtIva = document.getElementById('txtIva');
        const txtTotal = document.getElementById('txtTotal');

        function money(n){
            const x = Number(n || 0);
            return x.toFixed(3);
        }

        function buildOptions(selectedId = ''){
            let html = `<option value="">-- Seleccione --</option>`;
            for(const p of productos){
                const sel = (p.id === selectedId) ? 'selected' : '';
                html += `<option value="${p.id}" data-costo="${p.costo}" ${sel}>${p.id} - ${p.nombre}</option>`;
            }
            return html;
        }

        function renumber(){
            const rows = tbody.querySelectorAll('tr');
            rows.forEach((tr, i) => {
                tr.querySelector('select').name = `items[${i}][id_producto]`;
                tr.querySelector('.inpQty').name = `items[${i}][cantidad]`;
                tr.querySelector('.inpCost').name = `items[${i}][costo]`;
            });
        }

        function calcRow(tr){
            const qty = parseInt(tr.querySelector('.inpQty').value || '0', 10);
            const cost = parseFloat(tr.querySelector('.inpCost').value || '0');
            const sub = (qty > 0 ? qty : 0) * (cost > 0 ? cost : 0);
            tr.querySelector('.txtSub').value = money(sub);
            return sub;
        }

        function calcTotals(){
            let subtotal = 0;
            tbody.querySelectorAll('tr').forEach(tr => subtotal += calcRow(tr));
            const iva = subtotal * IVA_RATE;
            const total = subtotal + iva;

            txtSubtotal.value = money(subtotal);
            txtIva.value = money(iva);
            txtTotal.value = money(total);
        }

        // 🔒 Bloquear duplicados en selects
        function actualizarSelectsProductos(){
            const seleccionados = Array.from(document.querySelectorAll('.selProd'))
                .map(sel => sel.value)
                .filter(v => v);

            document.querySelectorAll('.selProd').forEach(select => {
                Array.from(select.options).forEach(option => {
                    if(!option.value) return;
                    option.disabled =
                        seleccionados.includes(option.value) &&
                        option.value !== select.value;
                });
            });
        }

        // 🔒 Bloquear letras en campos numéricos
        function soloNumeros(e){
            e.target.value = e.target.value.replace(/[^0-9.]/g, '');
        }

        function addRow(){
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select class="form-select selProd">
                        ${buildOptions()}
                    </select>
                </td>
                <td class="text-center">
                    <input type="number"
                           min="1"
                           class="form-control inpQty text-center"
                           value="1">
                </td>
                <td>
                    <input type="number"
                           step="0.001"
                           min="0"
                           class="form-control inpCost text-end bg-light"
                           readonly
                           style="pointer-events:none;">
                </td>
                <td>
                    <input type="text"
                           class="form-control txtSub text-end"
                           value="0.000"
                           readonly>
                </td>
                <td class="text-end">
                    <button type="button"
                            class="btn btn-outline-danger btn-sm btnDel">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
            renumber();

            const sel = tr.querySelector('.selProd');
            const qty = tr.querySelector('.inpQty');
            const cost = tr.querySelector('.inpCost');

            sel.addEventListener('change', (e) => {
                const opt = e.target.selectedOptions[0];
                cost.value = opt ? opt.dataset.costo : 0;
                calcTotals();
                actualizarSelectsProductos();
            });

            qty.addEventListener('input', (e) => {
                soloNumeros(e);
                calcTotals();
            });

            tr.querySelector('.btnDel').addEventListener('click', () => {
                tr.remove();
                renumber();
                calcTotals();
                actualizarSelectsProductos();
            });

            actualizarSelectsProductos();
            calcTotals();
        }

        btnAdd.addEventListener('click', addRow);

        addRow();
        document.addEventListener('DOMContentLoaded', actualizarSelectsProductos);
    </script>

</x-adminlayout>
