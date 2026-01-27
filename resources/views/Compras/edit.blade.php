<x-adminlayout>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Editar Orden de Compra</h2>
            <p class="text-muted mb-0">Actualización de orden de compra</p>
        </div>

        <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="fa-solid fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    @if($soloLectura)
        <div class="alert alert-warning shadow-sm border-0">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            Esta orden está anulada. Solo se permite visualizar la información.
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $productosJs = $productos->map(function($p){
            return [
                'id'     => $p->id_producto,
                'nombre' => $p->pro_descripcion,
                'costo'  => (float)($p->pro_precio_venta ?? 0),
            ];
        })->values();

        $detallesIniciales = $compra->detalles->map(function($d){
            return [
                'id_producto' => $d->id_producto,
                'cantidad'    => (int)$d->pxo_cantidad,
                'costo'       => (float)$d->pxo_valor,
            ];
        })->values();
    @endphp

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form id="formCompra" method="POST" action="{{ route('compras.update', $compra->id_compra) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">ID</label>
                        <input type="text" class="form-control" value="{{ $compra->id_compra }}" disabled>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Proveedor</label>
                        <select name="id_proveedor" class="form-select" required {{ $soloLectura ? 'disabled' : '' }}>
                            @foreach($proveedores as $p)
                                @php
                                    $selected = old('id_proveedor', $compra->id_proveedor) == $p->id_proveedor;
                                    $isAct = ($p->estado_prv === 'ACT');
                                    $disabled = (!$isAct && !$selected) ? 'disabled' : '';
                                @endphp
                                <option value="{{ $p->id_proveedor }}"
                                    {{ $selected ? 'selected' : '' }}
                                    {{ $disabled }}>
                                    {{ $p->id_proveedor }} - {{ $p->prv_nombre }}
                                    {{ !$isAct ? '(No disponible)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Fecha</label>
                        <input type="datetime-local"
                               name="oc_fecha_hora"
                               class="form-control bg-light"
                               value="{{ old('oc_fecha_hora', \Carbon\Carbon::parse($compra->oc_fecha_hora)->format('Y-m-d\TH:i')) }}"
                               readonly
                               style="pointer-events:none;">
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Detalle de Productos</h5>

                    @if(!$soloLectura)
                        <button type="button" id="btnAdd" class="btn btn-outline-primary btn-sm rounded-3">
                            <i class="fa-solid fa-plus me-2"></i> Agregar producto
                        </button>
                    @endif
                </div>

                <div id="msgDetalle" class="alert alert-warning d-none mb-3"></div>

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

                <div class="row mt-3">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="mb-2">
                                    <label class="form-label mb-1">Subtotal</label>
                                    <input type="text" id="txtSubtotal" class="form-control text-end" value="0.000" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mb-1">IVA (12%)</label>
                                    <input type="text" id="txtIva" class="form-control text-end" value="0.000" readonly>
                                </div>
                                <div>
                                    <label class="form-label mb-1 fw-bold">Total</label>
                                    <input type="text" id="txtTotal" class="form-control text-end fw-bold" value="0.000" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    @if(!$soloLectura)
                        <button class="btn btn-success rounded-3">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Guardar cambios
                        </button>
                        <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary rounded-3">Cancelar</a>
                    @else
                        <a href="{{ route('compras.index') }}" class="btn btn-primary rounded-3">
                            <i class="fa-solid fa-check me-2"></i> OK
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const soloLectura = @json($soloLectura);
                const productos = @json($productosJs);
                const detallesIniciales = @json($detallesIniciales);

                const IVA_RATE = 0.12;

                const tbody = document.querySelector('#detalleTable tbody');
                const btnAdd = document.getElementById('btnAdd');
                const msg = document.getElementById('msgDetalle');

                const txtSubtotal = document.getElementById('txtSubtotal');
                const txtIva = document.getElementById('txtIva');
                const txtTotal = document.getElementById('txtTotal');

                function money(n){
                    return Number(n || 0).toFixed(3);
                }

                function buildOptions(selectedId = ''){
                    let html = `<option value="">-- Seleccione --</option>`;
                    for(const p of productos){
                        const sel = String(p.id) === String(selectedId) ? 'selected' : '';
                        html += `<option value="${p.id}" data-costo="${p.costo}" ${sel}>
                    ${p.id} - ${p.nombre}
                </option>`;
                    }
                    return html;
                }

                function renumber(){
                    tbody.querySelectorAll('tr').forEach((tr, i) => {
                        tr.querySelector('select').name = `items[${i}][id_producto]`;
                        tr.querySelector('.inpQty').name = `items[${i}][cantidad]`;
                        tr.querySelector('.inpCost').name = `items[${i}][costo]`;
                    });
                }

                function calcRow(tr){
                    const qty = parseInt(tr.querySelector('.inpQty').value || '0', 10);
                    const cost = parseFloat(tr.querySelector('.inpCost').value || '0');
                    const sub = Math.max(qty,0) * Math.max(cost,0);
                    tr.querySelector('.txtSub').value = money(sub);
                    return sub;
                }

                function calcTotals(){
                    let subtotal = 0;
                    tbody.querySelectorAll('tr').forEach(tr => subtotal += calcRow(tr));
                    txtSubtotal.value = money(subtotal);
                    txtIva.value = money(subtotal * IVA_RATE);
                    txtTotal.value = money(subtotal * (1 + IVA_RATE));
                }

                function addRow(item = null){
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                <td>
                    <select class="form-select selProd" required ${soloLectura ? 'disabled' : ''}>
                        ${buildOptions(item?.id_producto)}
                    </select>
                </td>
                <td class="text-center">
                    <input type="number" min="1" class="form-control inpQty text-center"
                        value="${item?.cantidad ?? 1}" required ${soloLectura ? 'disabled' : ''}>
                </td>
                <td>
                    <input type="number" step="0.001" min="0"
                        class="form-control inpCost text-end bg-light"
                        value="${item?.costo ?? 0}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control txtSub text-end" readonly>
                </td>
                <td class="text-end">
                    ${soloLectura ? '' : `
                        <button type="button" class="btn btn-outline-danger btn-sm btnDel">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    `}
                </td>
            `;

                    tbody.appendChild(tr);
                    renumber();
                    calcTotals();

                    if(!soloLectura){
                        tr.querySelector('.selProd').addEventListener('change', e => {
                            tr.querySelector('.inpCost').value =
                                e.target.selectedOptions[0]?.dataset.costo || 0;
                            calcTotals();
                        });

                        tr.querySelector('.inpQty').addEventListener('input', calcTotals);
                        tr.querySelector('.btnDel')?.addEventListener('click', () => {
                            tr.remove();
                            renumber();
                            calcTotals();
                        });
                    }
                }

                // 🔥 AQUÍ SE PRECARGA CORRECTAMENTE
                if(detallesIniciales.length > 0){
                    detallesIniciales.forEach(d => addRow(d));
                } else {
                    addRow();
                }

                btnAdd?.addEventListener('click', () => addRow());

            });
        </script>

    </x-adminlayout>
